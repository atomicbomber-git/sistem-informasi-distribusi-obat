<?php

namespace App\Http\Livewire;

use App\Enums\MessageState;
use App\Enums\TipeMutasiStock;
use App\Models\FakturPenjualan;
use App\Models\Produk;
use App\Models\Stock;
use App\Support\Formatter;
use App\Support\HasValidatorThatEmitsErrors;
use App\Support\SessionHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class FakturPenjualanCreate extends Component
{
    use HasValidatorThatEmitsErrors;

    public Collection $itemFakturPenjualans;
    public $nomor;
    public $waktu_pengeluaran;
    public $pelanggan;

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

    public function submit()
    {
        $data = $this->validateAndEmitErrors([
            "nomor" => ["required", "integer", Rule::unique(FakturPenjualan::class)],
            "pelanggan" => ["required", "string"],
            "waktu_pengeluaran" => ["required", "date_format:Y-m-d\TH:i"],
            "itemFakturPenjualans" => ["required", "array"],
            "itemFakturPenjualans.*.produk_kode" => ["required", Rule::exists(Produk::class, "kode")],
            "itemFakturPenjualans.*.jumlah" => ["required", "numeric", "gte:0"],
            "itemFakturPenjualans.*.harga_satuan" => ["required", "numeric", "gte:0"],
        ]);

        DB::beginTransaction();

        $fakturPenjualan = FakturPenjualan::create([
            "nomor" => $data["nomor"],
            "pelanggan" => $data["pelanggan"],
            "waktu_pengeluaran" => $data["waktu_pengeluaran"],
        ]);

        foreach ($data["itemFakturPenjualans"] as $key => $dataItemFakturPenjualan) {
            /** @var Produk $produk */
            $produk = Produk::query()
                ->withQuantityInHand()
                ->findOrFail($dataItemFakturPenjualan["produk_kode"]);

            if ($produk->quantity_in_hand < $dataItemFakturPenjualan["jumlah"]) {
                DB::rollBack();

                throw $this->emitErrors(
                    ValidationException::withMessages([
                        "itemFakturPenjualans.{$key}.jumlah" => "Jumlah penjualan tidak boleh melebihi stock yang ada."
                    ])
                );
            }

            $itemFakturPenjualan = $fakturPenjualan->itemFakturPenjualans()->create([
                "produk_kode" => $dataItemFakturPenjualan["produk_kode"],
                "jumlah" => $dataItemFakturPenjualan["jumlah"],
                "harga_satuan" => $dataItemFakturPenjualan["harga_satuan"],
            ]);

            /* Now actually create the transactions */
            /** @var Collection|Stock[] $stocks */
            $stocks = $produk->stocks()
                ->select("id", "jumlah")
                ->orderBy("expired_at")
                ->canBeSold()
                ->get();

            $remainder = $dataItemFakturPenjualan["jumlah"];

            foreach ($stocks as $stock) {
                $amountToBeTaken = $remainder > $stock->jumlah ?
                    $stock->jumlah :
                    $remainder;

                $stock->fill([
                    "jumlah" => $stock->jumlah - $amountToBeTaken
                ]);

                 $stock->mutasiStocks()->create([
                    "item_faktur_penjualan_id" => $itemFakturPenjualan->id,
                    "jumlah" => -$amountToBeTaken,
                    "tipe" => TipeMutasiStock::PENJUALAN,
                    "transacted_at" => $fakturPenjualan->waktu_pengeluaran,
                ]);

                $stock->save();

                $remainder -= $amountToBeTaken;

                if ($remainder === 0.0) {
                    break;
                }
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
        $this->nomor = FakturPenjualan::getNextId();
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
        ];
    }

    public function removeItem(string $key)
    {
        unset($this->itemFakturPenjualans[$key]);

        ray()->send($this->itemFakturPenjualans);
    }

    public function render()
    {
        return view('livewire.faktur-penjualan-create');
    }
}
