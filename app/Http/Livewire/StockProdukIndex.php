<?php

namespace App\Http\Livewire;

use App\Models\Produk;
use App\Models\StockBatch;
use App\Support\WithCustomPagination;
use App\Support\WithFilter;
use App\Support\WithSort;
use Livewire\Component;

class StockProdukIndex extends Component
{
    use WithCustomPagination, WithFilter, WithSort;

    public function render()
    {
        return view('livewire.stock-produk-index', [
            "produks" => Produk::query()
                ->sortBy($this->sortBy, $this->sortDirection, "nama")
                ->withQuantityInHand()
                ->paginate()
        ]);
    }
}
