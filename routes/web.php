<?php

use App\Http\Controllers\FakturPenjualanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProdukSearchController;
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


Route::resource("faktur-penjualan", FakturPenjualanController::class);
