<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "pelanggan";

    /** return \App\QueryBuilders\PelangganBuilder */
    public function newEloquentBuilder($query)
    {
        return new \App\QueryBuilders\PelangganBuilder($query);
    }

    public static function query(): \App\QueryBuilders\PelangganBuilder
    {
        return parent::query();
    }
}
