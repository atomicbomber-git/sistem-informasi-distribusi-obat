<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FakturPenjualan extends Model
{
    use HasFactory;

    protected $table = "faktur_penjualan";
    protected $primaryKey = "kode";
    protected $casts = [
        "kode" => "string"
    ];

    /** return \App\QueryBuilders\FakturPenjualanBuilder */
    public function newEloquentBuilder($query)
    {
        return new \App\QueryBuilders\FakturPenjualanBuilder($query);
    }

    public static function query(): \App\QueryBuilders\FakturPenjualanBuilder
    {
        return parent::query();
    }
}
