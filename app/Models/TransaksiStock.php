<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiStock extends Model
{
    use HasFactory;
    protected $table = "transaksi_stock";
    protected $guarded = [];

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }
}
