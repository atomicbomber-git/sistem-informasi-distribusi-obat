<?php

use App\BusinessLogic\PlannedStockMutation;
use App\Enums\StockStatus;
use App\Enums\TipeMutasiStock;
use App\Exceptions\ApplicationException;
use App\Models\MutasiStock;
use App\Models\Produk;
use App\Models\Stock;
use Database\Factories\ProdukFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;

uses(RefreshDatabase::class);

test("getPlannedFirstExpiredFirstOutMutations() works correctly", function () {
    /** @var Produk $produkA */
    $produkA = ProdukFactory::new()->create();
    $now = now()->toImmutable();

    $stockA = Stock::factory()->create(["produk_kode" => $produkA->kode, "jumlah" => 50, "expired_at" => $now->addWeeks(1), "status" => StockStatus::NORMAL]);
    $stockB = Stock::factory()->create(["produk_kode" => $produkA->kode, "jumlah" => 100, "expired_at" => $now->addWeeks(-1), "status" => StockStatus::NORMAL]);
    $stockC = Stock::factory()->create(["produk_kode" => $produkA->kode, "jumlah" => 100, "expired_at" => $now->addWeeks(3), "status" => StockStatus::NORMAL]);

    $expectation = new Collection();
    $expectation->push(new PlannedStockMutation($stockA->getKey(), 50));
    $expectation->push(new PlannedStockMutation($stockC->getKey(), 25));

    expect(
        $expectation->toArray()
    )->toEqualCanonicalizing(
        $produkA
            ->getFirstExpiredFirstOutMutations(75)
            ->toArray()
    );
});

test("getPlannedFirstExpiredFirstOutMutations() throws exception when not enough stock is available", function () {
    $now = now()->toImmutable();

    /** @var Produk $produkA */
    $produkA = ProdukFactory::new()->create();
    Stock::factory()->create(["produk_kode" => $produkA->kode, "jumlah" => 50, "expired_at" => $now->addWeeks(1), "status" => StockStatus::NORMAL]);
    Stock::factory()->create(["produk_kode" => $produkA->kode, "jumlah" => 100, "expired_at" => $now->addWeeks(3), "status" => StockStatus::NORMAL]);

    $this->expectException(ApplicationException::class);
    $produkA->getFirstExpiredFirstOutMutations(200);
});

test("withQuantityInHand() works correctly", function () {
    $now = now()->toImmutable();

    $produk = Produk::factory()->create();

    $stock = Stock::query()->create([
        "kode_batch" => (string) Str::uuid(),
        "produk_kode" => $produk->kode,
        "jumlah" => 0,
        "nilai_satuan" => 100_000,
        "status" => StockStatus::NORMAL,
        "expired_at" => $now->addYear(),
    ]);

    MutasiStock::query()->insert([
        [
            "jumlah" => 100,
            "stock_id" => $stock->id,
            "tipe" => TipeMutasiStock::PEMBELIAN,
            "transacted_at" => $now->subDays(50),
        ],
        [
            "jumlah" => 100,
            "stock_id" => $stock->id,
            "tipe" => TipeMutasiStock::PEMBELIAN,
            "transacted_at" => $now->subDays(40),
        ],
        [
            "jumlah" => 100,
            "stock_id" => $stock->id,
            "tipe" => TipeMutasiStock::PEMBELIAN,
            "transacted_at" => $now->subDays(30),
        ],
        [
            "jumlah" => 100,
            "stock_id" => $stock->id,
            "tipe" => TipeMutasiStock::PEMBELIAN,
            "transacted_at" => $now->subDays(20),
        ],
        [
            "jumlah" => 100,
            "stock_id" => $stock->id,
            "tipe" => TipeMutasiStock::PEMBELIAN,
            "transacted_at" => $now->subDays(10),
        ],
        [
            "jumlah" => -500,
            "stock_id" => $stock->id,
            "tipe" => TipeMutasiStock::PENJUALAN,
            "transacted_at" => $now->subDays(8),
        ],
        [
            "jumlah" => 500,
            "stock_id" => $stock->id,
            "tipe" => TipeMutasiStock::PENJUALAN,
            "transacted_at" => $now->subDays(4),
        ],
    ]);

    $stock->update(["jumlah" => 500]);

    expect(
        Produk::query()
            ->withQuantityInHand($now->subDays(20))
            ->find($produk->kode)
            ->quantity_in_hand
    )->toEqualWithDelta(400, 0.0001);

    expect(
        Produk::query()
            ->withQuantityInHand($now)
            ->find($produk->kode)
            ->quantity_in_hand
    )->toEqualWithDelta(500, 0.0001);

    expect(
        Produk::query()
            ->withQuantityInHand($now->subDays(5))
            ->find($produk->kode)
            ->quantity_in_hand
    )->toEqualWithDelta(0, 0.0001);
});