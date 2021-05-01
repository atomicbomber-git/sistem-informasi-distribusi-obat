<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Date;

class FakturPenjualan extends Model
{
    use HasFactory;

    protected $table = "faktur_penjualan";
    protected $guarded = [];

    const NOMOR_PREFIX_CODE = "KM-";

    protected $casts = [
        "waktu_pengeluaran" => DatetimeInputCast::class
    ];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public static function getNextNomor(): int
    {
        return (
                self::query()
                    ->whereYear("waktu_pengeluaran", now()->year)
                    ->whereMonth("waktu_pengeluaran", now()->month)
                    ->orderByDesc("nomor")
                    ->value("nomor") ?? 0
            ) + 1;
    }

    public function getPrefixedNomor(): string
    {
        return $this->getNomorPrefix() . '-' . $this->nomor;
    }

    public function getNomorPrefix(): string
    {
        return self::NOMOR_PREFIX_CODE . $this->getNomorYearMonthPrefixPart();
    }

    public function getNomorYearMonthPrefixPart(): string
    {
        return \Jenssegers\Date\Date::make($this->waktu_pengeluaran)->format("YM");
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
