<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    protected $keyType = "string";
    protected $table = "produk";
    public $incrementing = false;
    protected $primaryKey = "kode";

    protected $casts = [
        "kode" => "string"
    ];

    use HasFactory;
    protected $guarded = [];

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

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
