<?php

namespace App\Http\Livewire;

use App\Enums\MessageState;
use App\Models\ItemFakturPenjualan;
use App\Models\ItemReturPenjualan;
use App\Models\MutasiStock;
use App\Models\ReturPenjualan;
use App\Rules\ReturPenjualanNomorUnique;
use App\Support\HasValidatorThatEmitsErrors;
use App\Support\SessionHelper;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class ReturPenjualanEdit extends Component
{
    use HasValidatorThatEmitsErrors;

    public ReturPenjualan $returPenjualan;

    /** @var \Illuminate\Support\Collection|\App\Models\ItemReturPenjualan[] $itemReturPenjualans */
    public Collection $itemReturPenjualans;

    public function rules(): array
    {
        return [
            "returPenjualan.nomor" => ["required", "integer", new ReturPenjualanNomorUnique($this->returPenjualan, $this->returPenjualan->nomor)],
            "returPenjualan.waktu_pengembalian" => ["required", "date_format:Y-m-d\TH:i"],
            "itemReturPenjualans.*.mutasi_stock_penjualan_id" => ["bail", "required", Rule::exists(MutasiStock::class, "id")],
            "itemReturPenjualans.*.jumlah" => [
                "required",
                "numeric",
                "gt:0",
                function ($attribute, $value, $fail) {
                    [$arrayName, $index,] = explode('.', $attribute);
                    $mutasiStockPenjualanIdAttribute = "{$arrayName}.{$index}.mutasi_stock_penjualan_id";

                    $passes = MutasiStock::query()
                        ->whereKey(data_get($this, $mutasiStockPenjualanIdAttribute))
                        ->whereRaw("-jumlah >= ?", [$value])
                        ->exists();

                    if (!$passes) {
                        $fail("Jumlah harus <= jumlah awal.");
                    }
                },
            ],
            "itemReturPenjualans.*.alasan" => ["required", "string", Rule::in(ItemReturPenjualan::REASONS)],
        ];
    }

    public function submit()
    {
        $this->validateAndEmitErrors();
        $this->validateInCaseOfDuplicatedItems();

        foreach ($this->itemReturPenjualans as $itemReturPenjualan) {
            $itemReturPenjualan->save();
        }

        $this->emitClearErrors();

        SessionHelper::flashMessage(
            __("messages.update.success"),
            MessageState::STATE_SUCCESS,
        );
    }

    private function validateInCaseOfDuplicatedItems(): void
    {
        $errorMessagesForDuplicatedDraftItems = $this->itemReturPenjualans
            ->groupBy(
                fn(ItemReturPenjualan $itemReturPenjualan) => $itemReturPenjualan->mutasi_stock_penjualan_id . "-" . $itemReturPenjualan->alasan,
                true
            )
            ->filter(fn(Collection $group) => $group->count() > 1)
            ->collapse()
            ->mapWithKeys(fn(ItemReturPenjualan $itemReturPenjualan, int $index) => [
                "itemReturPenjualans.{$index}.jumlah" => "Tidak boleh terdapat item ganda untuk pasangan item- kode batch - alasan",
                "itemReturPenjualans.{$index}.alasan" => "Tidak boleh terdapat item ganda untuk pasangan item- kode batch - alasan",
            ]);

        if ($errorMessagesForDuplicatedDraftItems->isNotEmpty()) {
            throw $this->emitValidationExceptionErrors(ValidationException::withMessages($errorMessagesForDuplicatedDraftItems->toArray()));
        }
    }

    public function mount(): void
    {
        $this->itemReturPenjualans = $this->returPenjualan
            ->itemReturPenjualans()
            ->get();
    }

    public function addItem(mixed $key)
    {
        MutasiStock::findOrFail($key);

        $this->itemReturPenjualans->push(new ItemReturPenjualan([
            "mutasi_stock_penjualan_id" => $key,
            "retur_penjualan_id" => $this->returPenjualan->getKey(),
            "jumlah" => 1,
            "alasan" => ItemReturPenjualan::EXPIRED,
        ]));
    }

    public function removeItem(mixed $key)
    {
        if ($this->itemReturPenjualans->has($key)) {
            $this->itemReturPenjualans->forget($key);
        }
    }

    private function pruneInvalidItems(): void
    {
        $this->itemReturPenjualans = $this->itemReturPenjualans->reject(fn(mixed $item) => !($item instanceof ItemReturPenjualan));
    }

    public function render()
    {
        $this->pruneInvalidItems();
        return view('livewire.retur-penjualan-edit');
    }
}
