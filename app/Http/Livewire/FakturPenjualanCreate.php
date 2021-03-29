<?php

namespace App\Http\Livewire;

use App\Enums\MessageState;
use App\Exceptions\ApplicationException;
use App\Models\FakturPenjualan;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Rules\FakturPenjualanNomorUnique;
use App\Support\Formatter;
use App\Support\HasValidatorThatEmitsErrors;
use App\Support\SessionHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Jenssegers\Date\Date;
use Livewire\Component;

class FakturPenjualanCreate extends Component
{
    use HasValidatorThatEmitsErrors;

    public Collection $itemFakturPenjualans;
    public $nomor;
    public $waktu_pengeluaran;
    public $diskon;
    public $pajak;
    public FakturPenjualan $fakturPenjualan;

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
                bcsub(1, bcdiv(($item_faktur_pembelian["diskon"] ?: 0), 100))
            );
    }

    public function rules()
    {
        return [
            "fakturPenjualan.nomor" => ["required", "integer", new FakturPenjualanNomorUnique($this->fakturPenjualan)],
            "fakturPenjualan.pelanggan_id" => ["required", Rule::exists(Pelanggan::class, "id")],
            "fakturPenjualan.waktu_pengeluaran" => ["required", "date_format:Y-m-d\TH:i"],
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
        $data = $this->validateAndEmitErrors();

        DB::beginTransaction();

        $this->fakturPenjualan->save();

        foreach ($data["itemFakturPenjualans"] as $key => $dataItemFakturPenjualan) {
            $itemFakturPenjualan = $this->fakturPenjualan->itemFakturPenjualans()->create([
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
        $this->fakturPenjualan = new FakturPenjualan([
            "nomor" => FakturPenjualan::getNextId(),
            "pelanggan_id" => null,
            "diskon" => 0,
            "pajak" => 10,
            "waktu_pengeluaran" => now()->format("Y-m-d\TH:i:s"),
        ]);

        $this->itemFakturPenjualans = new Collection();
    }

    public function addItem(string $itemKey)
    {
        $produk = Produk::query()->withQuantityInHand()->findOrFail($itemKey);

        $this->itemFakturPenjualans[$itemKey] ??= [
            "produk" => $produk,
            "produk_kode" => $produk->kode,
            "jumlah" => 1,
            "harga_satuan" => Formatter::normalizedNumeral($produk->harga_satuan),
            "diskon" => 0,
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
