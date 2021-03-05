<?php

namespace App\Http\Livewire;

use App\Models\Produk;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;

class FakturPembelianCreate extends Component
{
    public Collection $item_faktur_pembelians;

    public function mount()
    {
        $this->item_faktur_pembelians = new Collection();
    }

    public function addItem(mixed $key)
    {
        $this->item_faktur_pembelians[$key] ??= [
            "produk" => Produk::query()->findOrFail($key),
            "produk_kode" => $key,
            "jumlah" => 1,
            "harga_satuan" => null,
            "subtotal" => 0,
        ];
    }

    public function removeItem(mixed $key)
    {
        unset($this->item_faktur_pembelians[$key]);
    }

    public function render(): Factory|View|Application
    {
        $itemFakturPembelian = $this->item_faktur_pembelians
            ->map(function (array $item_faktur_pembelian) {
                return array_merge($item_faktur_pembelian, [
                    "subtotal" =>
                        ($item_faktur_pembelian["harga_satuan"] ?: 0),
                        ($item_faktur_pembelian["jumlah"] ?: 0),
                ]);
            });

        return view('livewire.faktur-pembelian-create', [
            "itemFakturPembelians" => $itemFakturPembelian,
            "total" => $itemFakturPembelian->sum("subtotal")
        ]);
    }
}
