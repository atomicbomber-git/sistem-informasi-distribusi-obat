<?php

use App\BusinessLogic\PlannedStockMutation;
use App\Enums\StockStatus;
use App\Enums\TipeMutasiStock;
use App\Exceptions\ApplicationException;
use App\Models\MutasiStock;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Stock;
use Database\Factories\ProdukFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;

uses(RefreshDatabase::class);

test("Pelanggan search works", function () {
    Pelanggan::factory()
        ->count(100)
        ->create();

    $this->getJson(route("pelanggan.search"))
        ->assertJsonStructure([
            "results" => ["*" => ["id", "text"]],
            "pagination" => ["more"],
        ]);
});