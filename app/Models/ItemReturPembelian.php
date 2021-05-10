<?php

namespace App\Models;

use App\Enums\TipeMutasiStock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class ItemReturPembelian extends Model
{
    const EXPIRED = "expired";
    const DAMAGED = "damaged";

    const REASONS = [
        self::EXPIRED,
        self::DAMAGED,
    ];

    protected $table = "item_retur_pembelian";
    protected $guarded = [];
    protected $connection = "mysql";

    public function returPembelian(): BelongsTo
    {
        return $this->belongsTo(ReturPembelian::class);
    }

    public function itemFakturPembelian(): BelongsTo
    {
        return $this->belongsTo(ItemFakturPembelian::class);
    }

    public function mutasiStock(): HasOne
    {
        return $this->hasOne(MutasiStock::class);
    }

    public function commitStockTransaction(): void
    {
        DB::beginTransaction();

        $this->itemFakturPembelian->mutasiStock->stock()->update([
            "jumlah" => DB::raw("jumlah - {$this->jumlah}")
        ]);

        $this->mutasiStock()->create([
            "item_retur_pembelian_id" => $this->getKey(),
            "stock_id" => $this->itemFakturPembelian->mutasiStock->stock_id,
            "jumlah" => $this->jumlah,
            "tipe" => TipeMutasiStock::RETUR_PEMBELIAN,
            "transacted_at" => $this->returPembelian->waktu_pengembalian,
        ]);

        DB::commit();
    }

    public function rollbackStockTransaction(): void
    {
        DB::beginTransaction();

        $this->itemFakturPembelian->mutasiStock->stock()->update([
            "jumlah" => DB::raw("jumlah + {$this->jumlah}")
        ]);

        $this->mutasiStock()->forceDelete();
        DB::commit();
    }
}