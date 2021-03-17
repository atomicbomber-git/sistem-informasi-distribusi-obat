<?php

namespace App\Models;

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

    public function isDeletable(): bool
    {
        $transaction = TransaksiStock::query()
            ->where("item_faktur_pembelian_id", $this->getKey())
            ->first();

        return TransaksiStock::query()
            ->whereKeyNot($transaction->getKey())
            ->where("stock_id", $transaction->stock_id)
            ->where("transacted_at", ">", $transaction->created_at)
            ->doesntExist();
    }

    public function deleteCascade()
    {
        $stocks = Stock::query()
            ->whereHas("transaksi_stocks", function (Builder $builder) {
                $builder->where("item_faktur_pembelian_id", $this->getKey());
            })->get();

        foreach ($stocks as $stock) {
            $stock->transaksi_stocks()->delete();
            $stock->delete();
        }

        $this->delete();
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class);
    }
}
