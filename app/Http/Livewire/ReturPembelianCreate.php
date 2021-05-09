<?php

namespace App\Http\Livewire;

use App\Models\FakturPembelian;
use App\Models\ItemFakturPembelian;
use App\Models\ItemReturPembelian;
use App\Models\ReturPembelian;
use App\Rules\ReturPembelianNomorUnique;
use App\Support\HasValidatorThatEmitsErrors;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class ReturPembelianCreate extends Component
{
    use HasValidatorThatEmitsErrors;

    public ReturPembelian $returPembelian;

    /** @var \Illuminate\Database\Eloquent\Collection | ItemReturPembelian[] */
    public Collection $itemReturPembelians;

    public function rules()
    {
        return [
            "returPembelian.nomor" => ["required", "integer", new ReturPembelianNomorUnique($this->returPembelian)],
            "returPembelian.waktu_pengembalian" => ["required", "date_format:Y-m-d\TH:i"],
            "returPembelian.faktur_pembelian_kode" => ["required", Rule::exists(FakturPembelian::class, "kode")],
            "itemReturPembelians.*.item_faktur_pembelian_id" => ["required", Rule::exists(ItemReturPembelian::class, "id")],
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
        $this->returPembelian = new ReturPembelian([
            "nomor" => ReturPembelian::getNextNomor()
        ]);

        $this->itemReturPembelians = new Collection();
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

    private function validateInCaseOfDuplicatedItems(): void
    {
        $errorMessagesForDuplicatedDraftItems = $this->itemReturPembelians
            ->groupBy(
                fn(ItemReturPembelian $itemReturPembelian) => $itemReturPembelian->item_faktur_pembelian_id . "-" . $itemReturPembelian->alasan,
                true
            )
            ->filter(fn(\Illuminate\Support\Collection $group) => $group->count() > 1)
            ->collapse()
            ->mapWithKeys(fn(ItemReturPembelian $itemReturPenjualan, int $index) => [
                "itemReturPembelians.{$index}.jumlah" => "Tidak boleh terdapat item ganda untuk pasangan item - kode batch - alasan",
                "itemReturPembelians.{$index}.alasan" => "Tidak boleh terdapat item ganda untuk pasangan item - kode batch - alasan",
            ]);

        if ($errorMessagesForDuplicatedDraftItems->isNotEmpty()) {
            throw $this->emitValidationExceptionErrors(ValidationException::withMessages($errorMessagesForDuplicatedDraftItems->toArray()));
        }
    }

    public function submit()
    {
        $validatedData = $this->validateAndEmitErrors();
        $this->validateInCaseOfDuplicatedItems();
    }

    public function pruneInvalidData(): void
    {
        $this->itemReturPembelians = $this->itemReturPembelians->filter(function (mixed $item) {
            return $item instanceof ItemReturPembelian;
        });
    }

    public function render()
    {
        $this->pruneInvalidData();
        return view('livewire.retur-pembelian-create');
    }
}
