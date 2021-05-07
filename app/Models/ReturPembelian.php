<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Jenssegers\Date\Date;

class ReturPembelian extends Model
{
    use HasFactory;
    protected $table = "retur_pembelian";
    protected $guarded = [];

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
        // TODO: Fix this one
        return "KM-RJB" . $this->getNomorYearMonthPrefixPart();
    }

    public function getNomorYearMonthPrefixPart(): string
    {
        if (!$this->waktu_pengembalian) {
            return "";
        }

        return Date::make($this->waktu_pengembalian)->format("YM");
    }
}
