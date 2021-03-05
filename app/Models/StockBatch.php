<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockBatch extends Model
{
    use HasFactory;

    protected $table = "stock_batch";
    protected $primaryKey = "kode_batch";
    public $incrementing = false;

    protected $casts = [
        "kode_batch" => "string",
    ];

    protected $guarded = [];
}
