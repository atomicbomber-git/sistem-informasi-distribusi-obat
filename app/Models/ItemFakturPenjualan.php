<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemFakturPenjualan extends Model
{
    use HasFactory;
    protected $table = "item_faktur_pembelian";
    protected $guarded = [];

    protected $casts = [
        "harga_satuan" => "float",
        "jumlah" => "float",
        "expired_at" => "datetime:Y-m-d",
    ];
}
