<?php

namespace App\Http\Livewire;

use App\Models\FakturPenjualan;
use App\Models\ItemFakturPenjualan;
use App\Models\Produk;
use App\Support\HasValidatorThatEmitsErrors;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Livewire\Component;

class FakturPenjualanEdit extends Component
{
    use HasValidatorThatEmitsErrors;

    public FakturPenjualan $fakturPenjualan;

    /** @var array | ItemFakturPenjualan[] */
    public Collection $itemFakturPenjualans;

    public \Illuminate\Support\Collection $originalItemKeys;
    public array $removedOriginalItemKeys;


    public function total(): float
    {
        $sum = $this->itemFakturPenjualans->reduce(function ($acc, ItemFakturPenjualan $next) {
            return bcadd($acc, $next->getSubtotal());
        }, "0");

        $discountedSum = bcmul($sum, bcdiv(bcsub(100, $this->fakturPenjualan->diskon), 100));
        return bcmul($discountedSum, bcdiv(bcadd(100, $this->fakturPenjualan->pajak), 100));
    }

    public function rules(): array
    {
        return [
            "fakturPenjualan.nomor" => [
                "required", "integer", Rule::unique(FakturPenjualan::class, "nomor")->ignore($this->fakturPenjualan->getOriginal("nomor"), "nomor")
            ],
            "fakturPenjualan.pelanggan" => ["required", "string"],
            "fakturPenjualan.waktu_pengeluaran" => ["required", "date"],
            "fakturPenjualan.diskon" => ["required", "numeric", "gte:0"],
            "fakturPenjualan.pajak" => ["required", "numeric", "gte:0"],

            "itemFakturPenjualans" => ["required", "array"],
            "itemFakturPenjualans.*.produk_kode" => ["required", Rule::exists(Produk::class, "kode")],
            "itemFakturPenjualans.*.jumlah" => ["required", "numeric", "gte:0"],
            "itemFakturPenjualans.*.harga_satuan" => ["required", "numeric", "gte:0"],
            "itemFakturPenjualans.*.diskon" => ["required", "numeric", "gte:0"],
        ];
    }

    public function submit()
    {
        $this->validateAndEmitErrors();

        DB::transaction(function () {
            $this->fakturPenjualan->save();

            foreach ($this->itemFakturPenjualans as $itemFakturPenjualan) {
                if ($itemFakturPenjualan->isModifiable() && $itemFakturPenjualan->isDirty()) {
                    $itemFakturPenjualan->rollbackStockTransaction();
                    $itemFakturPenjualan->save();
                    $itemFakturPenjualan->commitStockTransaction();
                }
            }
        });

        $this->redirect(route("faktur-penjualan.edit", $this->fakturPenjualan->refresh()));
    }

    public function mount()
    {
        $this->removedOriginalItemKeys = [];

        $this->itemFakturPenjualans = $this->fakturPenjualan->itemFakturPenjualans()
            ->with("produk")
            ->get();

        $this->originalItemKeys = $this->itemFakturPenjualans->keys();
    }

    public function addItem(string $itemKey)
    {
        if ($this->itemFakturPenjualans->firstWhere("produk_kode", "=", $itemKey)) {
            return;
        }

        $produk = Produk::query()->withQuantityInHand()->findOrFail($itemKey);

        $newItem = new ItemFakturPenjualan([
            "produk_kode" => $produk->kode,
            "jumlah" => 1,
            "diskon" => 0,
            "harga_satuan" => $produk->harga_satuan,
        ]);

        $newItem->setRelation("produk", $produk);
        $this->itemFakturPenjualans->push($newItem);
    }

    public function removeItem(string $key)
    {
        if ($this->originalItemKeys->contains($key)) {
            $this->removedOriginalItemKeys[$key] = true;
        } else {
            $this->itemFakturPenjualans->reject($key);
        }
    }

    public function restoreItem(string $key)
    {
        unset($this->removedOriginalItemKeys[$key]);
    }

    public function render()
    {
        return view('livewire.faktur-penjualan-edit');
    }
}
