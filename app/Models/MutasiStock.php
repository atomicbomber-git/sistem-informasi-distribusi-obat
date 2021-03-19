<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MutasiStock extends Model
{
    use HasFactory;
    protected $table = "mutasi_stock";
    protected $guarded = [];

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function item_faktur_pembelian(): BelongsTo
    {
        return $this->belongsTo(ItemFakturPembelian::class);
    }

    public function item_faktur_penjualan(): BelongsTo
    {
        return $this->belongsTo(ItemFakturPenjualan::class);
    }
}
