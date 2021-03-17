<?php

namespace App\Http\Livewire;

use App\Models\Produk;
use Illuminate\Support\Collection;
use Livewire\Component;

class FakturPenjualanCreate extends Component
{
    public Collection $itemFakturPenjualans;
    public $waktu_pengeluaran;
    public $pelanggan;

    public function mount()
    {
        $this->itemFakturPenjualans = new Collection();
    }

    public static function total(Collection $item_faktur_pembelians): float
    {
        return $item_faktur_pembelians->sum(function (array $item_faktur_pembelian) {
            return static::subTotal($item_faktur_pembelian);
        });
    }

    public static function subTotal(array $item_faktur_pembelian): float
    {
        return ($item_faktur_pembelian["harga_satuan"] ?: 0) * ($item_faktur_pembelian["jumlah"] ?: 0);
    }

    public function addItem(string $itemKey)
    {
        $this->itemFakturPenjualans[$itemKey] ??= [
            "produk" => Produk::query()->withQuantityInHand()->findOrFail($itemKey),
            "jumlah" => 1,
            "harga_satuan" => 0,
        ];
    }

    public function removeItem(string $key)
    {
        unset($this->itemFakturPenjualans[$key]);
    }

    public function render()
    {
        return view('livewire.faktur-penjualan-create');
    }
}
