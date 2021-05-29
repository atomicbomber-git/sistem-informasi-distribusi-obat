<?php

namespace App\Http\Livewire;

use App\Enums\MessageState;
use App\Models\FakturPembelian;
use App\Models\ItemFakturPembelian;
use App\Models\ItemReturPembelian;
use App\Models\ReturPembelian;
use App\Rules\ReturPembelianNomorUnique;
use App\Support\HasValidatorThatEmitsErrors;
use App\Support\SessionHelper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class ReturPembelianEdit extends Component
{
    use HasValidatorThatEmitsErrors;

    public ReturPembelian $returPembelian;

    /** @var \Illuminate\Database\Eloquent\Collection | ItemReturPembelian[] */
    public Collection $itemReturPembelians;

    public function rules()
    {
        return [
            "returPembelian.nomor" => ["required", "integer", new ReturPembelianNomorUnique($this->returPembelian, $this->returPembelian->nomor ?: null) ],
            "returPembelian.waktu_pengembalian" => ["required", "date_format:Y-m-d\TH:i"],
            "returPembelian.faktur_pembelian_kode" => ["required", Rule::exists(FakturPembelian::class, "kode")],
            "itemReturPembelians.*.item_faktur_pembelian_id" => ["required", Rule::exists(ItemFakturPembelian::class, "id")],
            "itemReturPembelians.*.jumlah" => ["required", "gt:0"],
            "itemReturPembelians.*.alasan" => ["required", Rule::in(ItemReturPembelian::REASONS)],
        ];
    }

    public function updated($attribute, $value): void
    {
        if ($attribute === "returPembelian.faktur_pembelian_kode") {
            $this->emitFakturPembelianChangedEvent();
        }
    }

    public function emitFakturPembelianChangedEvent()
    {
        $this->emit(
            "fakturPembelianChanged",
            route("faktur-pembelian.search-item", FakturPembelian::find($this->returPembelian->faktur_pembelian_kode)),
        );
    }

    public function mount()
    {
        $this->itemReturPembelians = $this->returPembelian
            ->itemReturPembelians()
            ->get();
    }

    public function removeItem(mixed $key)
    {
        $this->itemReturPembelians->forget($key);
    }

    public function addItem(mixed $key)
    {
        $itemFakturPembelian = ItemFakturPembelian::query()->findOrFail($key);

        $this->itemReturPembelians->put($key,
            new ItemReturPembelian([
                "item_faktur_pembelian_id" => $key,
                "itemFakturPembelian" => $itemFakturPembelian,
                "alasan" => ItemReturPembelian::DAMAGED,
            ])
        );
    }

    public function submit()
    {
        $this->validateAndEmitErrors();
        $this->validateInCaseOfDuplicatedItems();
        $this->validateInCaseJumlahInEachLineExceedsJumlahInItemFakturPenjualan();

        DB::beginTransaction();

        $this->returPembelian->save();

        foreach ($this->itemReturPembelians as $itemReturPembelian) {
            if ($itemReturPembelian->isClean()) continue;

            if ($itemReturPembelian->exists) {
                (clone $itemReturPembelian)->forceFill(
                    $itemReturPembelian->getOriginal()
                )->rollbackStockTransaction();
            }

            $itemReturPembelian->save();
            $itemReturPembelian->commitStockTransaction();
        }

        DB::commit();

        SessionHelper::flashMessage(
            __("messages.create.success"),
            MessageState::STATE_SUCCESS,
        );

        $this->redirect(route("retur-pembelian.index"));
    }

    private function validateInCaseOfDuplicatedItems(): void
    {
        $errorMessagesForDuplicatedDraftItems = $this->itemReturPembelians
            ->groupBy(
                fn(ItemReturPembelian $itemReturPembelian) => $itemReturPembelian->item_faktur_pembelian_id . "-" . $itemReturPembelian->alasan,
                true
            )
            ->filter(fn(\Illuminate\Support\Collection $group) => $group->count() > 1)
            ->collapse()
            ->mapWithKeys(fn(ItemReturPembelian $itemReturPembelian, int $index) => [
                "itemReturPembelians.{$index}.jumlah" => "Tidak boleh terdapat item ganda untuk pasangan item - kode batch - alasan",
                "itemReturPembelians.{$index}.alasan" => "Tidak boleh terdapat item ganda untuk pasangan item - kode batch - alasan",
            ]);

        if ($errorMessagesForDuplicatedDraftItems->isNotEmpty()) {
            throw $this->emitValidationExceptionErrors(ValidationException::withMessages($errorMessagesForDuplicatedDraftItems->toArray()));
        }
    }

    private function validateInCaseJumlahInEachLineExceedsJumlahInItemFakturPenjualan(): void
    {
        $sumOfJumlahByItemFakturPenjualanId = $this->itemReturPembelians
            ->groupBy("item_faktur_pembelian_id", true)
            ->mapWithKeys(function (\Illuminate\Support\Collection $group, int $item_faktur_pembelian_id) {
                return [$item_faktur_pembelian_id => $group->sum("jumlah")];
            });

        $errors = $this->itemReturPembelians
            ->filter(function (ItemReturPembelian $itemReturPembelian) use ($sumOfJumlahByItemFakturPenjualanId) {
                return
                    $sumOfJumlahByItemFakturPenjualanId[$itemReturPembelian->item_faktur_pembelian_id] >
                    $itemReturPembelian->itemFakturPembelian->jumlah
                    ;
            })->mapWithKeys(function (ItemReturPembelian $itemReturPembelian, int $key) {
                return [
                    "itemReturPembelians.{$key}.jumlah" => sprintf(
                        "Total jumlah untuk item dengan kode batch \"%s\" tidak boleh melebihi %d",
                        $itemReturPembelian->itemFakturPembelian->kode_batch,
                        $itemReturPembelian->itemFakturPembelian->jumlah,
                    )
                ];
            });

        if ($errors->isNotEmpty()) {
            throw $this->emitValidationExceptionErrors(ValidationException::withMessages($errors->toArray()));
        }
    }

    public function render()
    {
        $this->pruneInvalidData();
        return view('livewire.retur-pembelian-edit');
    }

    public function pruneInvalidData(): void
    {
        $this->itemReturPembelians = $this->itemReturPembelians->filter(function (mixed $item) {
            return $item instanceof ItemReturPembelian;
        });
    }
}
