<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemReturPembelian extends Model
{
    const EXPIRED = "expired";
    const DAMAGED = "damaged";
    const REASONS = [
        self::EXPIRED,
        self::DAMAGED,
    ];

    protected $table = "item_retur_pembelian";
    protected $guarded = [];
    protected $connection = "mysql";

    public function itemFakturPembelian(): BelongsTo
    {
        return $this->belongsTo(ItemFakturPembelian::class);
    }
}