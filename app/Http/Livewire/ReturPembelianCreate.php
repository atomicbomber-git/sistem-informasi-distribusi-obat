<?php

namespace App\Http\Livewire;

use App\Models\FakturPembelian;
use App\Models\FakturPenjualan;
use App\Models\ItemFakturPembelian;
use App\Models\ItemFakturPenjualan;
use App\Models\ItemReturPembelian;
use App\Models\ReturPembelian;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ReturPembelianCreate extends Component
{
    public ReturPembelian $returPembelian;

    /** @var \Illuminate\Database\Eloquent\Collection | ItemReturPembelian[] */
    public Collection $itemReturPembelians;

    public function rules()
    {
        return [
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
        $this->returPembelian = new ReturPembelian();
        $this->itemReturPembelians = new Collection();
    }

    public function addItem(mixed $key)
    {
        $itemFakturPembelian = ItemFakturPembelian::query()->findOrFail($key);

        $this->itemReturPembelians->push(
            new ItemReturPembelian([
                "item_faktur_pembelian_id" => $key,
                "itemFakturPembelian" => $itemFakturPembelian,
            ])
        );
    }

    public function render()
    {
        return view('livewire.retur-pembelian-create');
    }
}
