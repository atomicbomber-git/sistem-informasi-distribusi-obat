<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockBatch extends Model
{
    use HasFactory;

    protected $table = "stock_batch";
    protected $primaryKey = "kode_batch";
    public $incrementing = false;

    protected $casts = [
        "kode_batch" => "string",
        "expired_at" => "datetime:Y-m-d",
    ];

    protected $guarded = [];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function transaksi_stock(): HasMany
    {
        return $this->hasMany(TransaksiStock::class, "stock_kode_batch");
    }
}
