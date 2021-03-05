<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiStock extends Model
{
    use HasFactory;
    protected $table = "transaksi_stock";

    protected $guarded = [];
}
