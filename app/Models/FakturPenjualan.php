<?php

namespace App\Models;

use App\QueryBuilders\FakturPenjualanBuilder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Jenssegers\Date\Date;

class FakturPenjualan extends Model
{
    use HasFactory;

    const NOMOR_PREFIX_CODE = "KM-";
    protected $table = "faktur_penjualan";
    protected $guarded = [];

    protected $casts = [
        "waktu_pengeluaran" => DatetimeInputCast::class
    ];

    public function returPenjualan(): HasOne
    {
        return $this->hasOne(ReturPenjualan::class);
    }

    public static function getNextNomor(Carbon $referenceTime = null): int
    {
        $referenceTime ??= now();

        return (
                self::query()
                    ->whereYear("waktu_pengeluaran", $referenceTime->year)
                    ->whereMonth("waktu_pengeluaran", $referenceTime->month)
                    ->orderByDesc("nomor")
                    ->value("nomor") ?? 0
            ) + 1;
    }

    public static function query(): FakturPenjualanBuilder
    {
        return parent::query();
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class);
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
        return Date::make($this->waktu_pengeluaran)->format("YM");
    }

    public function itemFakturPenjualans(): HasMany
    {
        return $this->hasMany(ItemFakturPenjualan::class);
    }

    /** return \App\QueryBuilders\FakturPenjualanBuilder */
    public function newEloquentBuilder($query)
    {
        return new FakturPenjualanBuilder($query);
    }
}
