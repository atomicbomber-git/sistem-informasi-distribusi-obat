<?php

namespace App\Http\Livewire;

use App\BusinessLogic\PlannedStockMutation;
use App\Enums\MessageState;
use App\Enums\TipeMutasiStock;
use App\Exceptions\ApplicationException;
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

            $itemFakturPenjualan = $fakturPenjualan->itemFakturPenjualans()->create([
                "produk_kode" => $dataItemFakturPenjualan["produk_kode"],
                "jumlah" => $dataItemFakturPenjualan["jumlah"],
                "harga_satuan" => $dataItemFakturPenjualan["harga_satuan"],
            ]);

            try {
                $produk
                    ->getPlannedFirstExpiredFirstOutMutations($dataItemFakturPenjualan["jumlah"])
                    ->each(function (PlannedStockMutation $plan) use ($fakturPenjualan, $itemFakturPenjualan) {
                        $stock = Stock::find($plan->stockId);
                        $stock->update(["jumlah" => DB::raw("jumlah - {$plan->amount}")]);

                        $stock->mutasiStocks()->create([
                            "item_faktur_penjualan_id" => $itemFakturPenjualan->id,
                            "jumlah" => -$plan->amount,
                            "tipe" => TipeMutasiStock::PENJUALAN,
                            "transacted_at" => $fakturPenjualan->waktu_pengeluaran,
                        ]);
                    });
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
    }

    public function render()
    {
        return view('livewire.faktur-penjualan-create');
    }
}
