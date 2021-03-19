<?php

namespace App\Http\Livewire;

use App\Enums\MessageState;
use App\Models\FakturPembelian;
use App\Models\Produk;
use App\Support\HasValidatorThatEmitsErrors;
use App\Support\SessionHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class FakturPembelianCreate extends Component
{
    use HasValidatorThatEmitsErrors;

    public Collection $item_faktur_pembelians;
    public $kode;
    public $pemasok;
    public $waktu_penerimaan;
    public $item_faktur_pembelian_index = 0;

    public function mount()
    {
        $this->item_faktur_pembelians = new Collection();
    }

    public function submit()
    {
        $data = new Collection(
            $this->validateAndEmitErrors([
                "kode" => ["required", "string", Rule::unique(FakturPembelian::class)],
                "pemasok" => ["required", "string"],
                "waktu_penerimaan" => ["required", "date_format:Y-m-d\TH:i", "before_or_equal:now"],
                "item_faktur_pembelians" => ["required", "array"],
                "item_faktur_pembelians.*.produk_kode" => ["required", Rule::exists(Produk::class, "kode")],
                "item_faktur_pembelians.*.expired_at" => ["required", "date_format:Y-m-d"],
                "item_faktur_pembelians.*.jumlah" => ["required", "numeric", "gte:1"],
                "item_faktur_pembelians.*.harga_satuan" => ["required", "gte:0"],
                "item_faktur_pembelians.*.kode_batch" => ["required", "string"],
            ], [
                "waktu_penerimaan.before_or_equal" => "Waktu penerimaan tidak boleh terjadi di masa depan.",
            ])
        );

        DB::beginTransaction();

        $fakturPembelian = new FakturPembelian(
            $data->only(
                "pemasok", "waktu_penerimaan", "kode",
            )->toArray()
        );

        $fakturPembelian->save();

        foreach ($data["item_faktur_pembelians"] as $data_item_faktur_pembelian) {
            $itemFakturPembelian = $fakturPembelian->item_faktur_pembelians()
                ->create(
                    collect($data_item_faktur_pembelian)->only(
                        "produk_kode",
                        "kode_batch",
                        "jumlah",
                        "harga_satuan",
                        "expired_at"
                    )->toArray()
                );
            $itemFakturPembelian->applyStockTransaction();
        }

        SessionHelper::flashMessage(
            __("messages.create.success"),
            MessageState::STATE_SUCCESS,
        );

        DB::commit();

        $this->redirect(route("faktur-pembelian.index"));
    }

    public function addItem(mixed $key)
    {
        $this->item_faktur_pembelians[$this->item_faktur_pembelian_index++] ??= [
            "produk" => Produk::query()->findOrFail($key),
            "produk_kode" => $key,
            "expired_at" => null,
            "kode_batch" => null,
            "jumlah" => 1,
            "harga_satuan" => 0,
            "subtotal" => 0,
        ];
    }

    public function removeItem(mixed $key)
    {
        unset($this->item_faktur_pembelians[$key]);
    }

    public function render()
    {
        $itemFakturPembelian = $this->item_faktur_pembelians
            ->map(function (array $item_faktur_pembelian) {
                return array_merge($item_faktur_pembelian, [
                    "subtotal" =>
                        ($item_faktur_pembelian["harga_satuan"] ?: 0) *
                        ($item_faktur_pembelian["jumlah"] ?: 0)
                ]);
            });

        return view('livewire.faktur-pembelian-create', [
            "itemFakturPembelians" => $itemFakturPembelian,
            "total" => $itemFakturPembelian->sum("subtotal")
        ]);
    }
}
