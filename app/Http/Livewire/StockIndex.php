<?php

namespace App\Http\Livewire;

use App\Models\Produk;
use App\Models\Stock;
use App\Support\WithCustomPagination;
use App\Support\WithFilter;
use App\Support\WithSort;
use Livewire\Component;

class StockIndex extends Component
{
    use WithFilter, WithCustomPagination, WithSort;
    public Produk $produk;

    public function render()
    {
        return view('livewire.stock-index', [
            "stocks" => Stock::query()
                ->where("produk_kode", $this->produk->getKey())
                ->where("jumlah", ">", 0)
                ->paginate()
        ]);
    }
}
