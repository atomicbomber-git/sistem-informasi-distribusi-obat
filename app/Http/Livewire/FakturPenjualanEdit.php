<?php

namespace App\Http\Livewire;

use App\Enums\MessageState;
use App\Exceptions\ApplicationException;
use App\Models\FakturPenjualan;
use App\Models\ItemFakturPenjualan;
use App\Models\Produk;
use App\Support\Formatter;
use App\Support\HasValidatorThatEmitsErrors;
use App\Support\SessionHelper;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class FakturPenjualanEdit extends Component
{
    public FakturPenjualan $fakturPenjualan;

    use HasValidatorThatEmitsErrors;

    public Collection $itemFakturPenjualans;
    public $nomor;
    public $waktu_pengeluaran;
    public $pelanggan;
    public $diskon;
    public $pajak;

    public static function total(Collection $item_faktur_pembelians, $diskon, $pajak): float
    {
        $beforeDiscount = $item_faktur_pembelians->sum(function (array $item_faktur_pembelian) {
            return static::subTotal($item_faktur_pembelian);
        });

        $afterDiscount = bcmul($beforeDiscount, bcsub(1, bcdiv($diskon, 100)));
        $afterTax = bcmul($afterDiscount, bcadd(1, bcdiv($pajak, 100)));

        return $afterTax;
    }

    public static function subTotal(array $item_faktur_pembelian): float
    {
        return
            mbcmul(
                ($item_faktur_pembelian["harga_satuan"] ?: 0),
                ($item_faktur_pembelian["jumlah"] ?: 0),
                (bcdiv($item_faktur_pembelian["diskon"], 100))
            );
    }

    public function submit()
    {
        $data = $this->validateAndEmitErrors([
            "nomor" => ["required", "integer", Rule::unique(FakturPenjualan::class)],
            "pelanggan" => ["required", "string"],
            "waktu_pengeluaran" => ["required", "date_format:Y-m-d\TH:i"],
            "diskon" => ["required", "numeric", "gte:0"],
            "pajak" => ["required", "numeric", "gte:0"],
            "itemFakturPenjualans" => ["required", "array"],
            "itemFakturPenjualans.*.produk_kode" => ["required", Rule::exists(Produk::class, "kode")],
            "itemFakturPenjualans.*.jumlah" => ["required", "numeric", "gte:0"],
            "itemFakturPenjualans.*.harga_satuan" => ["required", "numeric", "gte:0"],
            "itemFakturPenjualans.*.diskon" => ["required", "numeric", "gte:0"],
        ]);

        DB::beginTransaction();

        $fakturPenjualan = FakturPenjualan::create([
            "nomor" => $data["nomor"],
            "pelanggan" => $data["pelanggan"],
            "waktu_pengeluaran" => $data["waktu_pengeluaran"],
            "diskon" => $data["diskon"],
            "pajak" => $data["pajak"],
        ]);

        foreach ($data["itemFakturPenjualans"] as $key => $dataItemFakturPenjualan) {
            $itemFakturPenjualan = $fakturPenjualan->itemFakturPenjualans()->create([
                "produk_kode" => $dataItemFakturPenjualan["produk_kode"],
                "jumlah" => $dataItemFakturPenjualan["jumlah"],
                "harga_satuan" => $dataItemFakturPenjualan["harga_satuan"],
                "diskon" => $dataItemFakturPenjualan["diskon"],
            ]);

            try {
                $itemFakturPenjualan->commitStockTransaction();
            } catch (ApplicationException $exception) {
                DB::rollBack();

                throw $this->emitErrors(
                    ValidationException::withMessages([
                        "itemFakturPenjualans.{$key}.jumlah" => "Jumlah penjualan tidak boleh melebihi stock yang ada."
                    ])
                );
            }
        }

        DB::commit();

        SessionHelper::flashMessage(
            __("messages.create.success"),
            MessageState::STATE_SUCCESS,
        );

        $this->redirect(route("faktur-penjualan.index"));
    }

    public function mount()
    {
        $this->nomor = $this->fakturPenjualan->nomor;
        $this->diskon = $this->fakturPenjualan->diskon;
        $this->pajak = $this->fakturPenjualan->pajak;
        $this->pelanggan = $this->fakturPenjualan->pelanggan;
        $this->waktu_pengeluaran = $this->fakturPenjualan->waktu_pengeluaran->format("Y-m-d\TH:i");

        $this->itemFakturPenjualans = $this->fakturPenjualan->itemFakturPenjualans->mapWithKeys(function (ItemFakturPenjualan $itemFakturPenjualan) {
            $itemFakturPenjualan->load([
                "produk" => function ($query) {
                    $query->withQuantityInHand();
                }
            ]);

            return [$itemFakturPenjualan->produk_kode => [
                "current_id" => $itemFakturPenjualan->id,
                "is_removed" => false,
                "produk" => $itemFakturPenjualan->produk,
                "produk_kode" => $itemFakturPenjualan->produk_kode,
                "jumlah" => $itemFakturPenjualan->jumlah,
                "harga_satuan" => Formatter::normalizedNumeral($itemFakturPenjualan->harga_satuan),
                "diskon" => $itemFakturPenjualan->diskon,
            ]];
        });
    }

    public function addItem(string $itemKey)
    {
        $produk = Produk::query()->withQuantityInHand()->findOrFail($itemKey);

        $this->itemFakturPenjualans[$itemKey] ??= [
            "current_id" => null,
            "is_removed" => true,
            "produk" => $produk,
            "produk_kode" => $produk->kode,
            "jumlah" => 1,
            "harga_satuan" => Formatter::normalizedNumeral($produk->harga_satuan),
            "diskon" => 0,
        ];
    }

    public function removeItem(string $key)
    {
        if ($this->itemFakturPenjualans[$key]["current_id"] === null) {
            unset($this->itemFakturPenjualans[$key]);
        }

        $this->itemFakturPenjualans->put($key, array_merge(
            $this->itemFakturPenjualans[$key], [
                "is_removed" => true,
            ]
        ));
    }

    public function restoreItem(string $key)
    {
        $this->itemFakturPenjualans->put($key, array_merge(
            $this->itemFakturPenjualans[$key], [
                "is_removed" => false,
            ]
        ));
    }

    public function render()
    {
        return view('livewire.faktur-penjualan-edit');
    }
}
