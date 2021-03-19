<?php


namespace App\QueryBuilders;


use App\Enums\StockStatus;
use App\Models\MutasiStock;
use Illuminate\Database\Eloquent\Builder;

class StockBuilder extends Builder
{
    use HasQueryBuilderHelpers;

    public function withOriginalMutation(): self
    {
        return $this->addSelect([
            "original_mutation_id" => MutasiStock::query()
                ->select("id")
                ->orderBy("transacted_at")
                ->limit(1)
        ])->with([
            "original_mutation",
            "original_mutation.item_faktur_pembelian",
        ]);
    }

    public function canBeSold(): self
    {
        return $this
            ->where("status", StockStatus::NORMAL)
            ->where("expired_at", ">", now());
    }
}