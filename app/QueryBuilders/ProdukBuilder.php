<?php


namespace App\QueryBuilders;


use App\Models\Stock;
use Illuminate\Database\Eloquent\Builder;

class ProdukBuilder extends Builder
{
    use HasQueryBuilderHelpers;

    public function withQuantityInHand(): self
    {
        return $this->addSelect([
            "quantity_in_hand" => $this->quantityInHandQuery()
        ]);
    }

    public function hasQuantityInHand()
    {
        return $this->where(
            $this->quantityInHandQuery(),
            ">",
            0,
        );
    }

    public function quantityInHandQuery(): Builder
    {
        return Stock::query()
            ->selectRaw("COALESCE(SUM(stock.jumlah), 0)")
            ->whereColumn("stock.produk_kode", "=", "produk.kode")
            ->limit(1);
    }
}