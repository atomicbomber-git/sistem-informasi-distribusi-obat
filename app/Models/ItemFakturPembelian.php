<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ItemFakturPembelian extends Model
{
    use HasFactory;
    protected $table = "item_faktur_pembelian";
    protected $guarded = [];

    public function stock_batch(): HasOne
    {
        return $this->hasOne(StockBatch::class);
    }
}
