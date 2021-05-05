<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Jenssegers\Date\Date;

class ReturPenjualan extends Model
{
    use HasFactory;
    protected $table = "retur_penjualan";
    protected $guarded = [];

    protected $casts = [
        "waktu_pengembalian" => DatetimeInputCast::class
    ];

    public function getPrefixedNomor(): string
    {
        return $this->getNomorPrefix() . '-' . $this->nomor;
    }

    public function getNomorPrefix(): string
    {
        return "KM-RJB" . $this->getNomorYearMonthPrefixPart();
    }

    public function getNomorYearMonthPrefixPart(): string
    {
        if (!$this->waktu_pengembalian) {
            return "";
        }

        return Date::make($this->waktu_pengembalian)->format("YM");
    }

    public function fakturPenjualan(): BelongsTo
    {
        return $this->belongsTo(FakturPenjualan::class);
    }

    public function itemReturPenjualans(): HasMany
    {
        return $this->hasMany(ItemReturPenjualan::class);
    }
}
