<?php

use App\Http\Livewire\FakturPenjualanCreate;
use App\Models\FakturPembelian;
use App\Models\FakturPenjualan;
use App\Models\ItemFakturPembelian;
use App\Models\Produk;
use Database\Factories\ProdukFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Faker\faker;
use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

test("Can create faktur penjualan", function () {
    /** @var Produk $produkA */
    $produkA = ProdukFactory::new()->create();
    $productAKey = $produkA->getKey();

    /** @var Produk $produkB */
    $produkB = ProdukFactory::new()->create();
    $productBKey = $produkB->getKey();

     FakturPembelian::factory()
        ->has(
            ItemFakturPembelian::factory()
                ->state(new Sequence(
                    ["produk_kode" => $produkA->kode, "jumlah" => 100, "expired_at" => now()->addWeeks(1)->format("Y-m-d")],
                    ["produk_kode" => $produkA->kode, "jumlah" => 150, "expired_at" => now()->addWeeks(2)->format("Y-m-d")],
                    ["produk_kode" => $produkA->kode, "jumlah" => 200, "expired_at" => now()->addWeeks(3)->format("Y-m-d")],

                    ["produk_kode" => $produkB->kode, "jumlah" => 200, "expired_at" => now()->addWeeks(3)->format("Y-m-d")],
                ))->count(4)
            , "item_faktur_pembelians"
        )
        ->create();

    livewire(FakturPenjualanCreate::class)
        ->set("nomor", 1)
        ->set("waktu_pengeluaran", now()->format("Y-m-d\TH:i"))
        ->set("pelanggan", faker()->name)
        ->call("addItem", $productAKey)
        ->set("itemFakturPenjualans.{$productAKey}.jumlah", 500)
        ->call("submit")
        ->assertHasErrors(["itemFakturPenjualans.{$productAKey}.jumlah"])
    ;

    livewire(FakturPenjualanCreate::class)
        ->set("nomor", 1)
        ->set("waktu_pengeluaran", now()->format("Y-m-d\TH:i"))
        ->set("pelanggan", faker()->name)
        ->call("addItem", $productAKey)
        ->set("itemFakturPenjualans.{$productAKey}.jumlah", 150)
        ->call("addItem", $productBKey)
        ->set("itemFakturPenjualans.{$productBKey}.jumlah", 100)
        ->call("removeItem", $productBKey)
        ->call("submit")
        ->assertHasNoErrors();

    $faktur = FakturPenjualan::where("nomor", 1)->first();

    expect($faktur)->not()->toBe(null);
    expect($faktur->itemFakturPenjualans()->count())->toBe(1);

    expect(
        Produk::query()
            ->whereKey($productAKey)
            ->withQuantityInHand()
            ->value("quantity_in_hand")
    )->toEqualWithDelta(300, 0.0001);
});
