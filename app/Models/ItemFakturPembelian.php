<?php

namespace App\Models;

use App\Exceptions\ApplicationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ItemFakturPembelian extends Model
{
    use HasFactory;

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
        return new \App\QueryBuilders\ItemFakturPembelianBuilder($query);
    }

    public static function query(): \App\QueryBuilders\ItemFakturPembelianBuilder
    {
        return parent::query();
    }

    public function faktur_pembelian(): BelongsTo
    {
        return $this->belongsTo(FakturPembelian::class);
    }

    public function isModifiable(): bool
    {
        $transaction = MutasiStock::query()
            ->where("item_faktur_pembelian_id", $this->getKey())
            ->first();

        return MutasiStock::query()
            ->whereKeyNot($transaction->getKey())
            ->where("stock_id", $transaction->stock_id)
            ->where("transacted_at", ">", $transaction->created_at)
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
            $stock->delete();
        }

        $this->delete();
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, "produk_kode", "kode");
    }

    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class);
    }
}
