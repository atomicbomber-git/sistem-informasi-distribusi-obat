<?php

namespace App\Http\Livewire;

use App\Enums\MessageState;
use App\Exceptions\ApplicationException;
use App\Models\FakturPenjualan;
use App\Models\ItemFakturPenjualan;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Support\HasValidatorThatEmitsErrors;
use App\Support\SessionHelper;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
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
            "fakturPenjualan.pelanggan_id" => ["required", Rule::exists(Pelanggan::class, "id")],
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
        foreach (array_keys($this->removedOriginalItemKeys) as $removedOriginalItemKey) {
            $item = $this->itemFakturPenjualans[$removedOriginalItemKey];
            if ( ! $item->isModifiable() ) {
                throw $this->emitValidationExceptionErrors(ValidationException::withMessages([
                    "itemFakturPenjualans.{$removedOriginalItemKey}.jumlah" => $item->getUnmodifiableMessage(),
                ]));
            }
        }

        $this->validateAndEmitErrors();

        DB::transaction(function () {
            $this->fakturPenjualan->save();

            foreach ($this->itemFakturPenjualans as $key => $itemFakturPenjualan) {
                if ($itemFakturPenjualan->isModifiable() && $itemFakturPenjualan->isDirty()) {
                    $itemFakturPenjualan->rollbackStockTransaction();
                    $itemFakturPenjualan->save();

                    try {
                        $itemFakturPenjualan->commitStockTransaction();
                    } catch (ApplicationException $exception) {
                        throw $this->emitValidationExceptionErrors(ValidationException::withMessages([
                            "itemFakturPenjualans.{$key}.jumlah" => "Stock yang tersedia tidak mencukupi.",
                        ]));
                    }
                }
            }
        });

        SessionHelper::flashMessage(
            __("messages.update.success"),
            MessageState::STATE_SUCCESS,
        );

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

        $produk = Produk::query()
            ->withQuantityInHand()
            ->findOrFail($itemKey);

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
