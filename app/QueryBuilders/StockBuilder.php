<?php


namespace App\QueryBuilders;


use Illuminate\Database\Eloquent\Builder;

class StockBuilder extends Builder
{
    use HasQueryBuilderHelpers;

    public function withOriginalMutation()
    {
        return $this->addSelect([
            "original_mutation_id" => \App\Models\MutasiStock::query()
                ->select("id")
                ->orderBy("transacted_at")
                ->limit(1)
        ])->with([
            "original_mutation",
            "original_mutation.item_faktur_pembelian",
        ]);
    }
}