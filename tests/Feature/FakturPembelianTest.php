<?php

use App\Enums\TipeMutasiStock;
use App\Http\Livewire\FakturPembelianCreate;
use App\Http\Livewire\FakturPembelianEdit;
use App\Models\FakturPembelian;
use App\Models\FakturPenjualan;
use App\Models\ItemFakturPembelian;
use App\Models\ItemFakturPenjualan;
use App\Models\MutasiStock;
use App\Models\Produk;
use App\Models\Stock;
use Database\Factories\ProdukFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Faker\faker;
use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

test("Can create faktur pembelian", function () {
    $produkA = ProdukFactory::new()->create();
    $produkB = ProdukFactory::new()->create();
    $produkC = ProdukFactory::new()->create();

    $kode = (string)Str::uuid();
    $pemasok = faker()->company;
    $waktu_penerimaan = now()->format("Y-m-d\TH:i");

    livewire(FakturPembelianCreate::class)
        ->call("submit")
        ->assertHasErrors([
            "kode",
            "pemasok",
            "waktu_penerimaan",
            "item_faktur_pembelians",
        ]);

    livewire(FakturPembelianCreate::class)
        ->set("kode", $kode)
        ->set("pemasok", $pemasok)
        ->set("waktu_penerimaan", $waktu_penerimaan)
        ->call("addItem", $produkA->getKey())
        /* First Item */
        ->set("item_faktur_pembelians.0.expired_at", now()->addWeek()->format("Y-m-d"))
        ->set("item_faktur_pembelians.0.kode_batch", Str::random())
        ->set("item_faktur_pembelians.0.jumlah", 100)
        ->set("item_faktur_pembelians.0.harga_satuan", 100_000)
        /* Second Item */
        ->call("addItem", $produkB->getKey())
        ->set("item_faktur_pembelians.1.expired_at", now()->addWeek()->format("Y-m-d"))
        ->set("item_faktur_pembelians.1.kode_batch", Str::random())
        ->set("item_faktur_pembelians.1.jumlah", 200)
        ->set("item_faktur_pembelians.1.harga_satuan", 50_000)
        /* Third Item, to be deleted */
        ->call("addItem", $produkC->getKey())
        ->set("item_faktur_pembelians.2.expired_at", now()->addWeek()->format("Y-m-d"))
        ->set("item_faktur_pembelians.2.kode_batch", Str::random())
        ->set("item_faktur_pembelians.2.jumlah", 120)
        ->set("item_faktur_pembelians.2.harga_satuan", 70_000)
        ->call("removeItem", 2)
        ->call("submit")
        ->assertHasNoErrors();

    $faktur = FakturPembelian::query()
        ->where([
            "kode" => $kode,
            "pemasok" => $pemasok,
            "waktu_penerimaan" => $waktu_penerimaan,
        ])->first();

    expect($faktur)->not()->toBeNull();
    expect($faktur->item_faktur_pembelians()->count())->toBe(2);

    expect(MutasiStock::query()->count())->toBe(2);
    expect(Stock::query()->count())->toBe(2);

    $firstItem = $faktur->item_faktur_pembelians[0];
    expect($firstItem->isModifiable())->toBe(true);
    expect($firstItem->mutasiStocks()->count())->toBe(1);

    $firstMutasiStock = $firstItem->mutasiStocks[0];
    expect($firstMutasiStock->tipe)->toBe(TipeMutasiStock::PEMBELIAN);
    expect($firstMutasiStock->jumlah)->toEqualWithDelta(100, 0.0001);

    $firstStock = $firstMutasiStock->stock;
    expect($firstStock->jumlah)->toEqualWithDelta(100, 0.0001);
    expect($firstStock->nilai_satuan)->toEqualWithDelta(100_000, 0.0001);

    $secondItem = $faktur->item_faktur_pembelians[1];
    expect($secondItem->isModifiable())->toBe(true);
    expect($secondItem->mutasiStocks()->count())->toBe(1);

    $secondMutasiStock = $secondItem->mutasiStocks[0];
    expect($secondMutasiStock->tipe)->toBe(TipeMutasiStock::PEMBELIAN);
    expect($secondMutasiStock->jumlah)->toEqualWithDelta(200, 0.0001);

    $secondStock = $secondMutasiStock->stock;
    expect($secondStock->jumlah)->toEqualWithDelta(200, 0.0001);
    expect($secondStock->nilai_satuan)->toEqualWithDelta(50_000, 0.0001);
});

test("Can edit faktur pembelian", function () {
    $produkA = ProdukFactory::new()->create();
    $produkB = ProdukFactory::new()->create();

    $fakturPembelian = FakturPembelian::factory(["waktu_penerimaan" => now()])
        ->has(
            ItemFakturPembelian::factory()
                ->state(new Sequence(
                    ["id" => 1, "produk_kode" => $produkA->kode, "jumlah" => 100, "expired_at" => now()->addWeeks(1)->format("Y-m-d")],
                    ["id" => 2, "produk_kode" => $produkA->kode, "jumlah" => 150, "expired_at" => now()->addWeeks(2)->format("Y-m-d")],
                    ["id" => 3, "produk_kode" => $produkA->kode, "jumlah" => 200, "expired_at" => now()->addWeeks(3)->format("Y-m-d")],
                    ["id" => 4, "produk_kode" => $produkB->kode, "jumlah" => 200, "expired_at" => now()->addWeeks(2)->format("Y-m-d")],
                ))
                ->count(3)
            , "item_faktur_pembelians"
        )
        ->create();

    /** @var \Illuminate\Support\Collection $items */
    $items = livewire(FakturPembelianEdit::class, ["fakturPembelian" => $fakturPembelian])
        ->get("item_faktur_pembelians");

    $toBeEditedIndex = $items->keys()->first(function ($key) use ($items) {
        return $items[$key]["current_id"] === 2;
    });

    $toBeEdited = $items[$toBeEditedIndex];

    expect($toBeEdited["jumlah"])->toEqualWithDelta(150, 0.0001);

    livewire(FakturPembelianEdit::class, ["fakturPembelian" => $fakturPembelian])
        ->set("item_faktur_pembelians.{$toBeEditedIndex}.jumlah", 100)
        ->call("submit")
        ->assertHasNoErrors();

    expect(
        Produk::query()
            ->whereKey($produkA->getKey())
            ->withQuantityInHand()
            ->value("quantity_in_hand")
    )->toEqualWithDelta(400, 0.0001);


});

test("Can't edit faktur pembelian when an item has been used in a faktur penjualan.", function () {
    $produkA = ProdukFactory::new()->create();
    $produkB = ProdukFactory::new()->create();

    $fakturPembelian = FakturPembelian::factory([
        "waktu_penerimaan" => now()->subDays(10)
    ])->has(
        ItemFakturPembelian::factory()
            ->state(new Sequence(
                ["id" => 1, "produk_kode" => $produkA->kode, "jumlah" => 100, "expired_at" => now()->addWeeks(1)->format("Y-m-d")],
                ["id" => 2, "produk_kode" => $produkA->kode, "jumlah" => 150, "expired_at" => now()->addWeeks(2)->format("Y-m-d")],
                ["id" => 3, "produk_kode" => $produkA->kode, "jumlah" => 200, "expired_at" => now()->addWeeks(3)->format("Y-m-d")],
                ["id" => 4, "produk_kode" => $produkB->kode, "jumlah" => 200, "expired_at" => now()->addWeeks(2)->format("Y-m-d")],
            ))
            ->count(3)
        , "item_faktur_pembelians"
    )->create();


    FakturPenjualan::factory()
        ->state([
            "waktu_pengeluaran" => now()->addDays(10)
        ])
        ->has(
            ItemFakturPenjualan::factory()
                ->state([
                    "produk_kode" => $produkA->kode,
                    "jumlah" => 200,
                    "harga_satuan" => $produkA->harga_satuan * 1.5,
                ])
                ->count(1)
        )
        ->create();

    /** @var \Illuminate\Support\Collection $items */
    $items = livewire(FakturPembelianEdit::class, ["fakturPembelian" => $fakturPembelian])
        ->get("item_faktur_pembelians");

    $supposedlyUneditableItemIndex = $items->keys()->first(function ($key) use ($items) {
        return $items[$key]["current_id"] === 2;
    });

    $supposedlyEditableItemIndexA = $items->keys()->first(function ($key) use ($items) {
        return $items[$key]["current_id"] === 3;
    });

    $supposedlyEditableItemIndexB = $items->keys()->first(function ($key) use ($items) {
        return $items[$key]["current_id"] === 3;
    });
    
    livewire(FakturPembelianEdit::class, ["fakturPembelian" => $fakturPembelian])
        ->set("item_faktur_pembelians.{$supposedlyUneditableItemIndex}.jumlah", 100)
        ->call("submit")
        ->assertHasErrors("item_faktur_pembelians.{$supposedlyUneditableItemIndex}.kode_batch");

    livewire(FakturPembelianEdit::class, ["fakturPembelian" => $fakturPembelian])
        ->set("item_faktur_pembelians.{$supposedlyEditableItemIndexA}.jumlah", 500)
        ->set("item_faktur_pembelians.{$supposedlyEditableItemIndexA}.harga_satuan", 100_000)
        ->set("item_faktur_pembelians.{$supposedlyEditableItemIndexB}.jumlah", 1000)
        ->set("item_faktur_pembelians.{$supposedlyEditableItemIndexB}.harga_satuan", 200_000)
        ->call("submit")
        ->assertHasNoErrors();
});

