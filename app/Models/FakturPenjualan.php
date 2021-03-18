<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FakturPenjualan extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $table = "faktur_penjualan";
    protected $primaryKey = "nomor";

    protected $casts = [
        "waktu_pengeluaran" => "datetime:Y-m-d\TH:i",
    ];

    protected $guarded = [];

    const ID_PREFIX = "KM-";

    public static function getNextId(): int
    {
        return (
                self::query()
                    ->orderByDesc("nomor")
                    ->value("nomor") ?? 0
            ) + 1;
    }

    public function getId(): string
    {
        return self::ID_PREFIX . $this->getKey();
    }

    public function itemFakturPenjualans(): HasMany
    {
        return $this->hasMany(ItemFakturPenjualan::class);
    }
}
