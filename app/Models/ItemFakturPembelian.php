<?php

namespace App\Models;

use App\Enums\StockStatus;
use App\Enums\TipeMutasiStock;
use App\Exceptions\ApplicationException;
use App\QueryBuilders\ItemFakturPembelianBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Kirschbaum\PowerJoins\PowerJoins;
use OwenIt\Auditing\Contracts\Auditable;

class ItemFakturPembelian extends Model implements Auditable
{
    use HasFactory, PowerJoins, \OwenIt\Auditing\Auditable;

    protected $table = "item_faktur_pembelian";
    protected $guarded = [];

    protected $casts = [
        "harga_satuan" => "float",
        "jumlah" => "float",
        "expired_at" => "datetime:Y-m-d",
    ];

    /** return \App\QueryBuilders\ItemFakturPembelianBuilder */
    public function newEloquentBuilder($query)
    {
        return new ItemFakturPembelianBuilder($query);
    }

    public static function query(): ItemFakturPembelianBuilder
    {
        return parent::query();
    }

    public function faktur_pembelian(): BelongsTo
    {
        return $this->belongsTo(FakturPembelian::class);
    }

    public function applyStockTransaction(): void
    {
        $stock = new Stock([
            "kode_batch" => $this->kode_batch,
            "produk_kode" => $this->produk_kode,
            "jumlah" => $this->jumlah,
            "nilai_satuan" => $this->harga_satuan,
            "expired_at" => $this->expired_at,
            "status" => StockStatus::NORMAL,
        ]);

        $stock->save();

        $stock->mutasiStocks()->create([
            "item_faktur_pembelian_id" => $this->id,
            "jumlah" => $this->jumlah,
            "tipe" => TipeMutasiStock::PEMBELIAN,
            "transacted_at" => $this->faktur_pembelian->waktu_penerimaan,
        ]);
    }

    public function isModifiable(): bool
    {
        $mutation = MutasiStock::query()
            ->where("item_faktur_pembelian_id", $this->getKey())
            ->first();

        return MutasiStock::query()
            ->whereKeyNot($mutation->getKey())
            ->where("stock_id", $mutation->stock_id)
            ->where("transacted_at", ">", $mutation->transacted_at)
            ->doesntExist();
    }

    public function getUnmodifiableMessage(): string
    {
        return "Produk \"{$this->produk->nama}\" dengan kode batch \"{$this->kode_batch}\" telah digunakan dalam transaksi lain dan tak dapat dihapus sebelum transaksi tersebut diubah.";
    }

    public function abortIfUnmodifiable(): void
    {
        if (!$this->isModifiable()) {
            throw new ApplicationException($this->getUnmodifiableMessage());
        }
    }

    public function destroyCascade()
    {
        $stocks = Stock::query()
            ->whereHas("mutasiStocks", function (Builder $builder) {
                $builder->where("item_faktur_pembelian_id", $this->getKey());
            })->get();

        foreach ($stocks as $stock) {
            $stock->mutasiStocks()->delete();
            $stock->forceDelete();
        }

        $this->delete();
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, "produk_kode", "kode");
    }

    public function mutasiStock(): hasOne
    {
        return $this->hasOne(MutasiStock::class);
    }
}
