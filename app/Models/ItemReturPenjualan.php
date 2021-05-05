<?php

namespace App\Models;

use App\Enums\StockStatus;
use App\Enums\TipeMutasiStock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class ItemReturPenjualan extends Model
{
    use HasFactory;

    const EXPIRED = "expired";
    const DAMAGED = "damaged";
    const REASONS = [
        self::EXPIRED,
        self::DAMAGED,
    ];
    protected $table = "item_retur_penjualan";
    protected $guarded = [];

    public function returPenjualan(): BelongsTo
    {
        return $this->belongsTo(ReturPenjualan::class);
    }

    public function mutasiStockPenjualan(): BelongsTo
    {
        return $this->belongsTo(MutasiStock::class);
    }

    public function mutasiStock(): HasOne
    {
        return $this->hasOne(MutasiStock::class);
    }

    public function commitStockTransaction(): void
    {
        $stock = Stock::findOrFail($this->mutasiStockPenjualan->stock_id);

        if ($this->alasan === self::DAMAGED) {
            $stock = $stock->replicate([
                "jumlah",
                "status",
            ]);

            $stock->fill([
                "jumlah" => $this->jumlah,
                "status" => StockStatus::DAMAGED,
            ])->save();
        } elseif ($this->alasan === self::EXPIRED) {
            $stock->update([
                "jumlah" => DB::raw("jumlah + {$this->jumlah}"),
            ]);
        }

        $stock->mutasiStocks()->create([
            "item_retur_penjualan_id" => $this->id,
            "jumlah" => $this->jumlah,
            "tipe" => TipeMutasiStock::RETUR_PENJUALAN,
            "transacted_at" => $this->returPenjualan->waktu_pengembalian,
        ]);

        $stock->save();
    }

    public function rollbackStockTransaction()
    {
        $this->mutasiStock->stock->update([
            "jumlah" => DB::raw("jumlah - {$this->mutasiStock->jumlah}")
        ]);

        if ($this->mutasiStock->stock->jumlah === 0) {
            $this->mutasiStock->stock->forceDelete();
        }

        $this->mutasiStock()->forceDelete();
    }
}
