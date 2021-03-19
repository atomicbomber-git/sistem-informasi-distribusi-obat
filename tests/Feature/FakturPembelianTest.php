<?php

use App\Enums\TipeMutasiStock;
use App\Http\Livewire\FakturPembelianCreate;
use App\Models\FakturPembelian;
use App\Models\MutasiStock;
use App\Models\Stock;
use Database\Factories\ProdukFactory;
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

