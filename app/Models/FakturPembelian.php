<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FakturPembelian extends Model
{
    use HasFactory;
    protected $table = "faktur_pembelian";
    public $incrementing = false;
    protected $primaryKey = "kode";

    protected $casts = [
        "kode" => "string"
    ];

    protected $guarded = [];

    /** return \App\QueryBuilders\FakturPenjualanBuilder */
    public function newEloquentBuilder($query)
    {
        return new \App\QueryBuilders\FakturPembelianBuilder($query);
    }

    public static function query(): \App\QueryBuilders\FakturPembelianBuilder
    {
        return parent::query();
    }

    public function item_faktur_pembelians(): HasMany
    {
        return $this->hasMany(
            ItemFakturPembelian::class,
            "faktur_pembelian_kode",
            "kode"
        );
    }
}
