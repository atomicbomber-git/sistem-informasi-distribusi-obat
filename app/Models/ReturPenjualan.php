<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReturPenjualan extends Model
{
    use HasFactory;
    protected $table = "retur_penjualan";
    protected $guarded = [];

    public function fakturPenjualan(): BelongsTo
    {
        return $this->belongsTo(FakturPenjualan::class);
    }

    public function itemReturPenjualans(): HasMany
    {
        return $this->hasMany(ItemReturPenjualan::class);
    }
}
