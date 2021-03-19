<?php

use App\BusinessLogic\PlannedStockMutation;
use App\Enums\StockStatus;
use App\Exceptions\ApplicationException;
use App\Models\Produk;
use App\Models\Stock;
use Database\Factories\ProdukFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;

uses(RefreshDatabase::class);

test("getPlannedFirstExpiredFirstOutMutations() works correctly", function () {
    /** @var Produk $produkA */
    $produkA = ProdukFactory::new()->create();

    $stockA = Stock::factory()->create(["produk_kode" => $produkA->kode, "jumlah" => 50, "expired_at" => now()->addWeeks(1), "status" => StockStatus::NORMAL]);
    $stockB = Stock::factory()->create(["produk_kode" => $produkA->kode, "jumlah" => 100, "expired_at" => now()->addWeeks(-1), "status" => StockStatus::NORMAL]);
    $stockC = Stock::factory()->create(["produk_kode" => $produkA->kode, "jumlah" => 100, "expired_at" => now()->addWeeks(3), "status" => StockStatus::NORMAL]);

    $expectation = new Collection();
    $expectation->push(new PlannedStockMutation($stockA->getKey(), 50));
    $expectation->push(new PlannedStockMutation($stockC->getKey(), 25));

    expect(
        $expectation->toArray()
    )->toEqualCanonicalizing(
        $produkA
            ->getPlannedFirstExpiredFirstOutMutations(75)
            ->toArray()
    );
});

test("getPlannedFirstExpiredFirstOutMutations() throws exception when not enough stock is available", function () {
    /** @var Produk $produkA */
    $produkA = ProdukFactory::new()->create();
    Stock::factory()->create(["produk_kode" => $produkA->kode, "jumlah" => 50, "expired_at" => now()->addWeeks(1), "status" => StockStatus::NORMAL]);
    Stock::factory()->create(["produk_kode" => $produkA->kode, "jumlah" => 100, "expired_at" => now()->addWeeks(3), "status" => StockStatus::NORMAL]);

    $this->expectException(ApplicationException::class);
    $produkA->getPlannedFirstExpiredFirstOutMutations(200);
});