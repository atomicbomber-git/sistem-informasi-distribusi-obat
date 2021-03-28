<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FakturPembelianController;
use App\Http\Controllers\FakturPenjualanController;
use App\Http\Controllers\PelangganSearchController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProdukInHandSearchController;
use App\Http\Controllers\ProdukSearchController;
use App\Http\Controllers\StockController;
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

Route::get("produk/search", ProdukSearchController::class)->name("produk.search");
Route::get("produk-in-hand/search", ProdukInHandSearchController::class)->name("produk-in-hand.search");
Route::get("pelanggan/search", PelangganSearchController::class)->name("pelanggan.search");


Route::resource("produk", ProdukController::class);
Route::resource("produk.stock", StockController::class)->parameter("stock", "stock-batch");
Route::resource("faktur-pembelian", FakturPembelianController::class);
Route::resource("faktur-penjualan", FakturPenjualanController::class);
Route::get("dashboard", DashboardController::class)->name("dashboard");



