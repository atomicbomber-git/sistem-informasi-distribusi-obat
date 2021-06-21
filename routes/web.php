<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EditAdminUserController;
use App\Http\Controllers\FakturPembelianController;
use App\Http\Controllers\FakturPembelianPrintController;
use App\Http\Controllers\FakturPenjualanController;
use App\Http\Controllers\FakturPenjualanPrintController;
use App\Http\Controllers\FakturPembelianSearchController;
use App\Http\Controllers\FakturPenjualanSearchController;
use App\Http\Controllers\ItemFakturPembelianSearchController;
use App\Http\Controllers\ItemFakturPenjualanSearchController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PelangganSearchController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\PemasokSearchController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProdukInHandSearchController;
use App\Http\Controllers\ProdukSearchController;
use App\Http\Controllers\ReturPembelianController;
use App\Http\Controllers\ReturPembelianPrintController;
use App\Http\Controllers\ReturPenjualanController;
use App\Http\Controllers\ReturPenjualanPrintController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UpdateAdminUserController;
use App\Http\Livewire\FakturPenjualanIndex;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes([
    "register" => false,
    "reset" => false,
    "confirm" => false,
    "verify" => false,
]);

Route::redirect("/", "dashboard");

Route::get("/audit", \App\Http\Controllers\AuditIndexController::class)->name("audit.index");
Route::get("admin/user/edit", EditAdminUserController::class)->name("admin.user.edit");
Route::patch("admin/user", UpdateAdminUserController::class)->name("admin.user.update");
Route::get("produk/search", ProdukSearchController::class)->name("produk.search");
Route::get("produk-in-hand/search", ProdukInHandSearchController::class)->name("produk-in-hand.search");
Route::resource("produk", ProdukController::class);
Route::get("pelanggan/search", PelangganSearchController::class)->name("pelanggan.search");
Route::resource("pelanggan", PelangganController::class);
Route::get("pemasok/search", PemasokSearchController::class)->name("pemasok.search");
Route::resource("pemasok", PemasokController::class);
Route::resource("produk.stock", StockController::class)->parameter("stock", "stock-batch");
Route::get("faktur-pembelian/search", FakturPembelianSearchController::class)->name("faktur-pembelian.search");
Route::get("faktur-pembelian/{faktur_pembelian}/print", FakturPembelianPrintController::class)->name("faktur-pembelian.print");
Route::get("faktur-pembelian/{faktur_pembelian}/search-item", ItemFakturPembelianSearchController::class)->name("faktur-pembelian.search-item");
Route::resource("faktur-pembelian", FakturPembelianController::class);
Route::get("faktur-penjualan/search", FakturPenjualanSearchController::class)->name("faktur-penjualan.search");
Route::get("faktur-penjualan/{faktur_penjualan}/print", FakturPenjualanPrintController::class)->name("faktur-penjualan.print");
Route::resource("faktur-penjualan", FakturPenjualanController::class);
Route::get("faktur-penjualan/{faktur_penjualan}/search-item", ItemFakturPenjualanSearchController::class)->name("faktur-penjualan.search-item");
Route::get("dashboard", DashboardController::class)->name("dashboard");
Route::resource("retur-penjualan", ReturPenjualanController::class);
Route::get("retur-penjualan/{retur_penjualan}/print", ReturPenjualanPrintController::class)->name("retur-penjualan.print");
Route::resource("retur-pembelian", ReturPembelianController::class);
Route::get("retur-pembelian/{retur_pembelian}/print", ReturPembelianPrintController::class)->name("retur-pembelian.print");


