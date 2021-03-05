<?php

use App\Http\Controllers\FakturPembelianController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProdukSearchController;
use App\Http\Controllers\StockProdukController;
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

Route::redirect("/", "produk");

Route::get("produk/search", ProdukSearchController::class)->name("produk.search");
Route::resource("produk", ProdukController::class);
Route::resource("faktur-pembelian", FakturPembelianController::class);

Route::resource("stock-produk", StockProdukController::class)->only("index", "show");

