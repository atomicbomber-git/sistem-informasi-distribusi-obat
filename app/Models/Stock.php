<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Stock extends Model
{
    use HasFactory;

    protected $table = "stock";

    protected $casts = [
        "kode_batch" => "string",
        "expired_at" => "datetime:Y-m-d",
    ];

    protected $guarded = [];

    /** return \App\QueryBuilders\StockBuilder */
    public function newEloquentBuilder($query)
    {
        return new \App\QueryBuilders\StockBuilder($query);
    }

    public static function query(): \App\QueryBuilders\StockBuilder
    {
        return parent::query();
    }

    public function original_mutation(): HasOne
    {
        return $this->hasOne(MutasiStock::class);
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function mutasiStocks(): HasMany
    {
        return $this
            ->hasMany(MutasiStock::class)
            ->orderByDesc("transacted_at");
    }
}
