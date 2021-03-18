<?php

namespace App\Http\Livewire;

use App\Enums\MessageState;
use App\Enums\TipeTransaksiStock;
use App\Models\FakturPembelian;
use App\Models\ItemFakturPembelian;
use App\Models\Produk;
use App\Models\Stock;
use App\Models\TransaksiStock;
use App\Support\HasValidatorThatEmitsErrors;
use App\Support\SessionHelper;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class FakturPembelianEdit extends Component
{
    use HasValidatorThatEmitsErrors;

    public FakturPembelian $fakturPembelian;

    public Collection $item_faktur_pembelians;
    public $kode;
    public $pemasok;
    public $waktu_penerimaan;
    public $item_faktur_pembelian_index = 0;

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
        $data = new Collection(
            $this->validateAndEmitErrors([
                "kode" => ["required", "string", Rule::unique(FakturPembelian::class)->ignore($this->fakturPembelian)],
                "pemasok" => ["required", "string"],
                "waktu_penerimaan" => ["required", "date_format:Y-m-d\TH:i", "before_or_equal:now"],
                "item_faktur_pembelians" => ["required", "array"],
                "item_faktur_pembelians.*.produk_kode" => ["required", Rule::exists(Produk::class, "kode")],
                "item_faktur_pembelians.*.expired_at" => ["required", "date_format:Y-m-d"],
                "item_faktur_pembelians.*.jumlah" => ["required", "numeric", "gte:1"],
                "item_faktur_pembelians.*.harga_satuan" => ["required", "gte:0"],
                "item_faktur_pembelians.*.kode_batch" => ["required", "string"],
                "item_faktur_pembelians.*.is_removed" => ["required", "boolean"],
                "item_faktur_pembelians.*.current_id" => [
                    "nullable",
                    Rule::exists(ItemFakturPembelian::class, "id")
                        ->where("faktur_pembelian_kode", $this->fakturPembelian->kode)
                ],
            ], [
                "waktu_penerimaan.before_or_equal" => "Waktu penerimaan tidak boleh terjadi di masa depan.",
            ])
        );

        $itemFakturPembeliansData = collect($data["item_faktur_pembelians"]);

        $itemFakturPembeliansData->transform(function (array $itemData) {
            if ($itemData["current_id"] !== null) {
                $itemData["is_modified"] = !ItemFakturPembelian::query()
                    ->where([
                        "id" => $itemData["current_id"],
                        "harga_satuan" => $itemData["harga_satuan"],
                        "jumlah" => $itemData["jumlah"],
                        "kode_batch" => $itemData["kode_batch"],
                    ])->exists();
            } else {
                $itemData["is_modified"] = false;
            }

            return $itemData;
        });

        $existingModifiedItemsData = $itemFakturPembeliansData
            ->filter(
                fn($item) => ($item["current_id"] !== null) && ($item["is_modified"] || $item["is_removed"])
            );

        DB::beginTransaction();

        foreach ($existingModifiedItemsData as $index => $existingItemData) {
            /** @var ItemFakturPembelian $itemFakturPembelian */
            $itemFakturPembelian = ItemFakturPembelian::query()
                ->findOrFail($existingItemData["current_id"]);

            if (!$itemFakturPembelian->isModifiable()) {
                throw ValidationException::withMessages([
                    "item_faktur_pembelians.{$index}.kode_batch" => $itemFakturPembelian->getUnmodifiableMessage(),
                ]);
            }

            $itemFakturPembelian->destroyCascade();
        }

        $itemsToBeCreated = $itemFakturPembeliansData
            ->filter(
                fn (array $itemData) => (
                    ($itemData["current_id"] !== null) && !$itemData["is_removed"] && $itemData["is_modified"] ||
                    ($itemData["current_id"] === null)
                )
            );

        /* Update the faktur itself */
        $this->fakturPembelian->update(collect($data)->only(
            "kode",
            "pemasok",
            "waktu_penerimaan",
        )->toArray());

        /* Update transacted_at fields */
        TransaksiStock::query()
            ->whereIn("item_faktur_pembelian_id", $itemFakturPembeliansData->pluck("id")->toArray())
            ->update(["transacted_at" => $data["waktu_penerimaan"]]);

        foreach ($itemsToBeCreated as $itemData) {
            $itemFakturPembelian = new ItemFakturPembelian(
                collect($itemData)->only(
                    "produk_kode",
                    "kode_batch",
                    "jumlah",
                    "harga_satuan",
                    "expired_at"
                )->toArray()
            );

            $this->fakturPembelian
                ->item_faktur_pembelians()
                ->save($itemFakturPembelian);

            $stock = new Stock([
                "kode_batch" => $itemData["kode_batch"],
                "produk_kode" => $itemFakturPembelian->produk_kode,
                "jumlah" => $itemFakturPembelian->jumlah,
                "nilai_satuan" => $itemFakturPembelian->harga_satuan,
                "expired_at" => $itemData["expired_at"],
            ]);

            $stock->save();

            $stock->transaksi_stocks()->create([
                "item_faktur_pembelian_id" => $itemFakturPembelian->id,
                "jumlah" => $itemFakturPembelian->jumlah,
                "tipe" => TipeTransaksiStock::PEMBELIAN,
                "transacted_at" => $this->fakturPembelian->waktu_penerimaan,
            ]);
        }

        DB::commit();

        SessionHelper::flashMessage(
            __("messages.update.success"),
            MessageState::STATE_SUCCESS,
        );

        $this->redirect(route("faktur-pembelian.edit", $this->fakturPembelian));
    }

    public function mount()
    {
        $this->fill([
            "kode" => $this->fakturPembelian->kode,
            "pemasok" => $this->fakturPembelian->pemasok,
            "waktu_penerimaan" => $this->fakturPembelian->waktu_penerimaan->format("Y-m-d\TH:i"),
        ]);

        $this->item_faktur_pembelians = ItemFakturPembelian::query()
            ->selectQualify(["id", "produk_kode", "jumlah", "harga_satuan", "expired_at", "kode_batch"])
            ->where("faktur_pembelian_kode", $this->fakturPembelian->kode)
            ->with("produk")
            ->sortBy("produk.nama")
            ->get()
            ->mapWithKeys(function (ItemFakturPembelian $itemFakturPembelian, int $index) {
                return [$index => [
                    "produk" => $itemFakturPembelian->produk,
                    "produk_kode" => $itemFakturPembelian->produk_kode,
                    "expired_at" => $itemFakturPembelian->expired_at->format("Y-m-d"),
                    "kode_batch" => $itemFakturPembelian->kode_batch,
                    "jumlah" => $itemFakturPembelian->jumlah,
                    "harga_satuan" => $itemFakturPembelian->harga_satuan,
                    "subtotal" => 0,
                    "is_removed" => false,
                    "current_id" => $itemFakturPembelian->id,
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
            "current_id" => null,
        ];
    }

    public function removeOrRestoreItem(mixed $key)
    {
        $item = $this->item_faktur_pembelians[$key];

        if ($item["current_id"] === null) {
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
