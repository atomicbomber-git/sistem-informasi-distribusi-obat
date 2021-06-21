<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Auditable;

class FakturPembelian extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, Auditable;

    protected $keyType = "string";
    protected $table = "faktur_pembelian";
    public $incrementing = false;
    protected $primaryKey = "kode";

    protected $casts = [
        "kode" => "string",
        "waktu_penerimaan" => "datetime:Y-m-d\TH:i",
    ];

    protected $guarded = [];

    public function returPembelian(): HasOne
    {
        return $this->hasOne(ReturPembelian::class);
    }


    /** return \App\QueryBuilders\FakturPenjualanBuilder */
    public function newEloquentBuilder($query)
    {
        return new \App\QueryBuilders\FakturPembelianBuilder($query);
    }

    public static function query(): \App\QueryBuilders\FakturPembelianBuilder
    {
        return parent::query();
    }

    public function pemasok(): BelongsTo
    {
        return $this->belongsTo(Pemasok::class);
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
