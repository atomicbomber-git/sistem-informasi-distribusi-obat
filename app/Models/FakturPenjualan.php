<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FakturPenjualan extends Model
{
    use HasFactory;
    protected $table = "faktur_penjualan";
    public $incrementing = false;
    protected $primaryKey = "kode";

    protected $casts = [
        "kode" => "string",
        "waktu_pengeluaran" => "datetime:Y-m-d\TH:i",
    ];

    protected $guarded = [];

    public function itemFakturPenjualans(): HasMany
    {
        return $this->hasMany(ItemFakturPenjualan::class);
    }
}
