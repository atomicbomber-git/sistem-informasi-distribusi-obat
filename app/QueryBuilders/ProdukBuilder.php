<?php


namespace App\QueryBuilders;


use App\Models\Stock;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;

class ProdukBuilder extends Builder
{
    use HasQueryBuilderHelpers;

    public function withQuantityInHand(DateTimeInterface $when = null): self
    {
        $when ??= now();

        return $this->addSelect([
            "quantity_in_hand" => $this->quantityInHandQuery($when)
        ]);
    }

    public function hasQuantityInHand($when = null)
    {
        return $this->where(
            $this->quantityInHandQuery($when ?? now()),
            ">",
            0,
        );
    }

    public function quantityInHandQuery(DateTimeInterface $when): Builder
    {
        return Stock::query()
            ->selectRaw("
                COALESCE(SUM(stock.jumlah), 0)
                - COALESCE(SUM((
                    SELECT COALESCE(SUM(mutasi_stock.jumlah), 0)
                        FROM mutasi_stock
                        WHERE mutasi_stock.stock_id = stock.id
                        AND transacted_at > ?
                        AND transacted_at <= ?
                )), 0)
            ", [$when, now()])
            ->whereColumn("stock.produk_kode", "=", "produk.kode")
            ->canBeSold()
            ->limit(1);
    }
}