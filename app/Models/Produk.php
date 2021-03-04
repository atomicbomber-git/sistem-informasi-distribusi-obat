<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = "produk";
    protected $primaryKey = "kode";

    protected $casts = [
        "kode" => "string"
    ];

    use HasFactory;
    protected $guarded = [];

    /** return \App\QueryBuilders\ProdukBuilder */
    public function newEloquentBuilder($query)
    {
        return new \App\QueryBuilders\ProdukBuilder($query);
    }

    public static function query(): \App\QueryBuilders\ProdukBuilder
    {
        return parent::query();
    }
}
