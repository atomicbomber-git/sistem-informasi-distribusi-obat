<?php

namespace App\Http\Livewire;

use App\Models\FakturPembelian;
use App\Models\ItemFakturPembelian;
use App\Models\Produk;
use Illuminate\Support\Collection;
use Livewire\Component;

class FakturPembelianEdit extends Component
{
    public FakturPembelian $fakturPembelian;

    public Collection $item_faktur_pembelians;
    public $kode;
    public $pemasok;
    public $waktu_penerimaan;
    public $item_faktur_pembelian_index = 0;

    public static function subTotal(array $item_faktur_pembelian): float {
        return ($item_faktur_pembelian["harga_satuan"] ?: 0) * ($item_faktur_pembelian["jumlah"] ?: 0);
    }

    public static function total(Collection $item_faktur_pembelians): float {
        return  $item_faktur_pembelians->sum(function (array $item_faktur_pembelian) {
            return static::subTotal($item_faktur_pembelian);
        });
    }

    public function mount()
    {

        $this->fill([
            "kode" => $this->fakturPembelian->kode,
            "pemasok" => $this->fakturPembelian->pemasok,
            "waktu_penerimaan" => $this->fakturPembelian->waktu_penerimaan->format("Y-m-d\TH:i"),
        ]);

        $this->item_faktur_pembelians = ItemFakturPembelian::query()
            ->where("faktur_pembelian_kode", $this->fakturPembelian->kode)
            ->with("produk")
            ->get()
            ->mapWithKeys(function (ItemFakturPembelian $itemFakturPembelian, int $index) {
                return [$index => [
                    "produk" => $itemFakturPembelian->produk,
                    "produk_kode" => $itemFakturPembelian->produk_kode,
                    "expired_at" => $itemFakturPembelian->stock_batch->expired_at->format("Y-m-d"),
                    "kode_batch" => $itemFakturPembelian->produk_kode,
                    "jumlah" => $itemFakturPembelian->jumlah,
                    "harga_satuan" => $itemFakturPembelian->harga_satuan,
                    "subtotal" => 0,

                    "is_removed" => false,
                    "is_new" => false,
                ]];
            });

        $this->item_faktur_pembelian_index = $this->item_faktur_pembelians->count();
    }

    public function addItem(mixed $key)
    {
        $this->item_faktur_pembelians[++$this->item_faktur_pembelian_index] ??= [
            "produk" => Produk::query()->findOrFail($key),
            "produk_kode" => $key,
            "expired_at" => null,
            "kode_batch" => null,
            "jumlah" => 1,
            "harga_satuan" => 0,
            "subtotal" => 0,

            "is_removed" => false,
            "is_new" => true,
        ];
    }

    public function removeOrRestoreItem(mixed $key)
    {
        $item = $this->item_faktur_pembelians[$key];

        if ($item["is_new"]) {
            unset($this->item_faktur_pembelians[$key]);
        } else {
            $this->item_faktur_pembelians[$key] = array_merge($item, [
                "is_removed" => !$this->item_faktur_pembelians[$key]["is_removed"],
            ]);
        }
    }


    public function render()
    {
        return view('livewire.faktur-pembelian-edit');
    }
}
