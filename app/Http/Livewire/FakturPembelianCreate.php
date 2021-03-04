<?php

namespace App\Http\Livewire;

use App\Models\Produk;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;

class FakturPembelianCreate extends Component
{
    public Collection $item_faktur_penjualans;
    public float $persentase_diskon_grosir;

    public function mount()
    {
        $this->item_faktur_penjualans = new Collection();
        $this->persentase_diskon_grosir = 0.0;
    }

    public function addItem(mixed $key)
    {
        $this->item_faktur_penjualans[$key] ??= [
            "produk" => Produk::query()->findOrFail($key),
            "produk_kode" => $key,
            "jumlah" => 1,
            "harga_satuan" => null,
            "persentase_diskon" => 0,
            "subtotal" => 0,
        ];
    }

    public function updated($attribute, $value)
    {
        if (
            Str::is("item_faktur_penjualans.*.persentase_diskon", $attribute) ||
            ($attribute === "persentase_diskon_grosir")
        ) {
            if ($value > 100) {
                $this->emit("set:{$attribute}", 100);
            } elseif ($value < 0) {
                $this->emit("set:{$attribute}", 0);
            }
        }
    }

    public function removeItem(mixed $key)
    {
        unset($this->item_faktur_penjualans[$key]);
    }

    public function render()
    {
        $itemFakturPenjualans = $this->item_faktur_penjualans
            ->map(function (array $item) {
                return array_merge($item, [
                    "subtotal" =>
                        ($item["harga_satuan"] ?: 0) *
                        ($item["jumlah"] ?: 0) *
                        (1 - ($item["persentase_diskon"] ?: 0) / 100)
                ]);
            });

        return view('livewire.faktur-pembelian-create', [
            "item_faktur_penjualans" => $itemFakturPenjualans,
            "total_before_bulk_discount" => $itemFakturPenjualans->sum("subtotal"),
            "total" => $itemFakturPenjualans->sum("subtotal") * (1 - ($this->persentase_diskon_grosir / 100))
        ]);
    }
}
