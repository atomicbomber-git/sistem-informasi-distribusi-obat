<?php

namespace App\Models;

use App\QueryBuilders\PemasokBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasok extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "pemasok";

    public static function query(): PemasokBuilder
    {
        return parent::query();
    }

    /** return \App\QueryBuilders\PemasokBuilder */
    public function newEloquentBuilder($query)
    {
        return new PemasokBuilder($query);
    }
}
