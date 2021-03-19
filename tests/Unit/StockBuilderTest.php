<?php

namespace Tests\QueryBuilders;

use App\Enums\StockStatus;
use Database\Factories\StockFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test("Stock::canBeSold() works correctly.", function () {
    StockFactory::new()
        ->state(new Sequence(
            ["id" => 1, "expired_at" => now()->addWeeks(-3), "status" => StockStatus::NORMAL],
            ["id" => 2, "expired_at" => now()->addWeeks(-2), "status" => StockStatus::NORMAL],
            ["id" => 3, "expired_at" => now()->addWeeks(-1), "status" => StockStatus::NORMAL],
            ["id" => 4, "expired_at" => now()->addWeeks(+1), "status" => StockStatus::NORMAL],
            ["id" => 5, "expired_at" => now()->addWeeks(+2), "status" => StockStatus::NORMAL],
            ["id" => 6, "expired_at" => now()->addWeeks(+3), "status" => StockStatus::DAMAGED],
        ))
        ->count(6)
        ->create();
});
