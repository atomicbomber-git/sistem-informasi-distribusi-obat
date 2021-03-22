<?php

namespace App\Models;

use App\BusinessLogic\PlannedStockMutation;
use App\Enums\TipeMutasiStock;
use App\Exceptions\ApplicationException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ItemFakturPenjualan extends Model
{
    use HasFactory;
    protected $table = "item_faktur_penjualan";
    protected $guarded = [];

    protected $casts = [
        "harga_satuan" => "float",
        "jumlah" => "float",
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function fakturPenjualan(): BelongsTo
    {
        return $this->belongsTo(FakturPenjualan::class);
    }

    public function mutasiStocks(): HasMany
    {
        return $this->hasMany(MutasiStock::class);
    }

    /**
     * @throws ApplicationException
     */
    public function applyStockTransaction(): void
    {
        /** @var Produk $produk */
        $produk = Produk::query()->findOrFail($this->produk_kode);

        $produk
            ->getPlannedFirstExpiredFirstOutMutations($this->jumlah)
            ->each(function (PlannedStockMutation $plan) {
                $stock = Stock::find($plan->stockId);
                $stock->update(["jumlah" => DB::raw("jumlah - {$plan->amount}")]);

                $stock->mutasiStocks()->create([
                    "item_faktur_penjualan_id" => $this->getKey(),
                    "jumlah" => -$plan->amount,
                    "tipe" => TipeMutasiStock::PENJUALAN,
                    "transacted_at" => $this->fakturPenjualan->waktu_pengeluaran,
                ]);
            });
    }
}
