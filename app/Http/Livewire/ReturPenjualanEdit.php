<?php

namespace App\Http\Livewire;

use App\Enums\MessageState;
use App\Models\ItemReturPenjualan;
use App\Models\MutasiStock;
use App\Models\ReturPenjualan;
use App\Rules\ReturPenjualanNomorUnique;
use App\Support\HasValidatorThatEmitsErrors;
use App\Support\SessionHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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
            "returPenjualan.nomor" => ["required", "integer", new ReturPenjualanNomorUnique($this->returPenjualan, $this->returPenjualan->nomor ?: null)],
            "returPenjualan.waktu_pengembalian" => ["required", "date_format:Y-m-d\TH:i"],
            "itemReturPenjualans.*.retur_penjualan_id" => ["required", Rule::exists(ReturPenjualan::class, "id")],
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
        $this->validateSumOfItemGroups();

        DB::beginTransaction();

        $this->returPenjualan->save();

        $deletedItemReturPenjualans = $this->returPenjualan
            ->itemReturPenjualans()
            ->whereNotIn("id", $this->itemReturPenjualans->pluck("id"))
            ->get();

        foreach ($deletedItemReturPenjualans as $deletedItemReturPenjualan) {
            $deletedItemReturPenjualan->rollbackStockTransaction();
            $deletedItemReturPenjualan->forceDelete();
        }

        foreach ($this->itemReturPenjualans as $itemReturPenjualan) {
            if ($itemReturPenjualan->isDirty()) {
                if ($itemReturPenjualan->exists) {
                    (clone $itemReturPenjualan)->forceFill(
                        $itemReturPenjualan->getOriginal()
                    )->rollbackStockTransaction();
                }

                $itemReturPenjualan->save();
                $itemReturPenjualan->commitStockTransaction();
            }
        }

        DB::commit();

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

    private function validateSumOfItemGroups(): void
    {
        $sumOfJumlahByMutasiStockPenjualanId = $this->itemReturPenjualans
            ->groupBy("mutasi_stock_penjualan_id", true)
            ->mapWithKeys(function (Collection $group, int $mutasi_stock_penjualan_id) {
                return [$mutasi_stock_penjualan_id => $group->sum("jumlah")];
            });

        $errors = $this->itemReturPenjualans
            ->filter(function (ItemReturPenjualan $itemReturPenjualan) use ($sumOfJumlahByMutasiStockPenjualanId) {
                return
                    (-$itemReturPenjualan->mutasiStockPenjualan->jumlah) <
                    $sumOfJumlahByMutasiStockPenjualanId[$itemReturPenjualan->mutasi_stock_penjualan_id];
            })->mapWithKeys(function (ItemReturPenjualan $itemReturPenjualan, int $key) {
                return [
                    "itemReturPenjualans.{$key}.jumlah" => sprintf(
                        "Total jumlah untuk item dengan kode batch \"%s\" tidak boleh melebihi %d",
                        $itemReturPenjualan->mutasiStockPenjualan->stock->kode_batch,
                        -$itemReturPenjualan->mutasiStockPenjualan->jumlah,
                    )
                ];
            });

        if ($errors->isNotEmpty()) {
            throw $this->emitValidationExceptionErrors(ValidationException::withMessages($errors->toArray()));
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

    public function render()
    {
        $this->pruneInvalidItems();
        return view('livewire.retur-penjualan-edit');
    }

    private function pruneInvalidItems(): void
    {
        $this->itemReturPenjualans = $this->itemReturPenjualans->reject(fn(mixed $item) => !($item instanceof ItemReturPenjualan));
    }
}
