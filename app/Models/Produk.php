<?php

namespace App\Models;

use App\BusinessLogic\PlannedStockMutation;
use App\Enums\TipeMutasiStock;
use App\Exceptions\ApplicationException;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    protected $keyType = "string";
    protected $table = "produk";
    public $incrementing = false;
    protected $primaryKey = "kode";

    protected $casts = [
        "kode" => "string"
    ];

    use HasFactory;
    protected $guarded = [];

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    /**
     * @param float $amount
     * @return Collection | PlannedStockMutation[]
     */
    public function getFirstExpiredFirstOutMutations(float $amount): Collection
    {
        // Cek jumlah stock yang ada dari Produk ini
        $quantityInHand = self::query()
            ->whereKey($this->getKey())
            ->withQuantityInHand()
            ->value("quantity_in_hand");

        // Jika permintaan ($amount) > jumlah yang ada ($quantityInHand), maka error
        if (bccomp($quantityInHand, $amount) === -1) {
            throw new ApplicationException("Stock tidak cukup untuk produk {$this->nama} dengan kode ({$this->kode})");
        }

        // Tarik data stock, diurutkan dari kolom expired_at, dengan syarat statusnya normal
        /** @var Collection | Stock[] $stocks */
        $stocks = $this->stocks()
            ->getQuery()
            ->select("id", "jumlah")
            ->orderBy("expired_at")
            ->canBeSold()
            ->get();

        // Tambahkan masing2 jumlah stock sampai totalnya sesuai permintaan
        $results = new Collection();
        $remainder = $amount;
        foreach ($stocks as $stock) {
            $amountToBeTaken = $remainder > $stock->jumlah ?
                $stock->jumlah :
                $remainder;

            $results->push(new PlannedStockMutation($stock->getKey(), $amountToBeTaken));
            $remainder = bcsub($remainder, $amountToBeTaken);

            if (bccomp($remainder, 0) === 0) {
                break;
            }
        }

        return $results;
    }

    /** return \App\QueryBuilders\ProdukBuilder */
    public function newEloquentBuilder($query)
    {
        return new \App\QueryBuilders\ProdukBuilder($query);
    }

    public static function query(): \App\QueryBuilders\ProdukBuilder
    {
        return parent::query();
    }
}
