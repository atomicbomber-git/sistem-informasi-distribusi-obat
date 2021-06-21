<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Jenssegers\Date\Date;
use OwenIt\Auditing\Contracts\Auditable;

class ReturPembelian extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = "retur_pembelian";
    protected $guarded = [];

    protected $casts = [
        "waktu_pengembalian" => DatetimeInputCast::class
    ];

    public function fakturPembelian(): BelongsTo
    {
        return $this->belongsTo(FakturPembelian::class);
    }

    public static function getNextNomor(Carbon $referenceTime = null): int
    {
        $referenceTime ??= now();

        return (
                self::query()
                    ->whereYear("waktu_pengembalian", $referenceTime->year)
                    ->whereMonth("waktu_pengembalian", $referenceTime->month)
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
        return "KM-RBB" . $this->getNomorYearMonthPrefixPart();
    }

    public function getNomorYearMonthPrefixPart(): string
    {
        if (!$this->waktu_pengembalian) {
            return "";
        }

        return Date::make($this->waktu_pengembalian)->format("YM");
    }

    public function itemReturPembelians(): HasMany
    {
        return $this->hasMany(ItemReturPembelian::class);
    }
}
