<?php

namespace App\Models;

use App\BusinessLogic\PlannedStockMutation;
use App\Enums\TipeMutasiStock;
use App\Exceptions\ApplicationException;
use App\QueryBuilders\ProdukBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class ItemFakturPenjualan extends Model
{
    use HasFactory;

    protected $table = "item_faktur_penjualan";
    protected $connection = "mysql";

    protected $casts = [
        "harga_satuan" => "float",
        "jumlah" => "float",
    ];

    protected $fillable = [
        "faktur_penjualan_nomor",
        "produk_kode",
        "jumlah",
        "harga_satuan",
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class)
            ->tap(function (ProdukBuilder $produkBuilder) {
                $produkBuilder->withQuantityInHand();
            });
    }

    public function fakturPenjualan(): BelongsTo
    {
        return $this->belongsTo(FakturPenjualan::class);
    }

    public function mutasiStocks(): HasMany
    {
        return $this->hasMany(MutasiStock::class);
    }

    public function getSubtotal(): string
    {
        return bcdiv(
            mbcmul(
                $this->jumlah ?: 0,
                $this->harga_satuan ?: 0,
                bcsub(100, $this->diskon ?: 0)
            ),
            100
        );
    }

    /**
     * @throws ApplicationException
     */
    public function commitStockTransaction(): void
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

    public function abortIfUnmodifiable(): void
    {
        if (!$this->isModifiable()) {
            throw new ApplicationException($this->getUnmodifiableMessage());
        }
    }

    public function isModifiable(): bool
    {
        // TODO: implement this accordingly
        return true;
    }

    public function getUnmodifiableMessage(): string
    {
        return "Produk \"{$this->produk->nama}\" dengan kode batch \"{$this->kode_batch}\" telah digunakan dalam operasi lain dan tak dapat dihapus sebelum operasi tersebut diubah.";
    }

    public function rollbackStockTransaction()
    {
        foreach ($this->mutasiStocks as $mutasiStock) {
            $mutasiStock->stock->decrement("jumlah", $mutasiStock->jumlah);
            $mutasiStock->forceDelete();
        }
    }
}
