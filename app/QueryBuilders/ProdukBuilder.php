<?php


namespace App\QueryBuilders;


use App\Models\StockBatch;
use Illuminate\Database\Eloquent\Builder;

class ProdukBuilder extends Builder
{
    use WithQueryBuilderHelpers;

    public function withQuantityInHand(): self
    {
        return $this->addSelect([
            "quantity_in_hand" => StockBatch::query()
                ->selectRaw("COALESCE(SUM(stock_batch.jumlah), 0)")
                ->whereColumn("stock_batch.produk_kode", "=", "produk.kode")
                ->limit(1)
        ]);
    }
}