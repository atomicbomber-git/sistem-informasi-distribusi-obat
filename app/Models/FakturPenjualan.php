<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FakturPenjualan extends Model
{
    use HasFactory;

    protected $table = "faktur_penjualan";
    protected $guarded = [];

    const NOMOR_PREFIX = "KM-";

    protected $casts = [
        "waktu_pengeluaran" => DatetimeInputCast::class
    ];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public static function getNextId(): int
    {
        return (
                self::query()
                    ->orderByDesc("nomor")
                    ->value("nomor") ?? 0
            ) + 1;
    }

    public function getNomor(): string
    {
        return self::NOMOR_PREFIX . $this->getKey();
    }

    public function itemFakturPenjualans(): HasMany
    {
        return $this->hasMany(ItemFakturPenjualan::class);
    }

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
