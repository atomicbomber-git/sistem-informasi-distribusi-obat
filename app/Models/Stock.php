<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stock extends Model
{
    use HasFactory;

    protected $table = "stock";

    protected $casts = [
        "kode_batch" => "string",
        "expired_at" => "datetime:Y-m-d",
    ];

    protected $guarded = [];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function transaksi_stocks(): HasMany
    {
        return $this->hasMany(TransaksiStock::class);
    }
}
