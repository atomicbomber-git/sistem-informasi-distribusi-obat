<?php


namespace App\BusinessLogic;


use App\Models\Stock;

class PlannedStockMutation
{
    public int $stockId;
    public float $amount;

    /**
     * PlannedStockMutation constructor.
     * @param int $stockId
     * @param float $amount
     */
    public function __construct(int $stockId, float $amount)
    {
        $this->stockId = $stockId;
        $this->amount = $amount;
    }
}