<?php

namespace App\Http\Livewire;

use App\Models\Produk;
use App\Support\WithCustomPagination;
use App\Support\WithDestroy;
use App\Support\WithFilter;
use App\Support\WithSort;
use Livewire\Component;

class ProdukIndex extends Component
{
    use WithFilter, WithCustomPagination, WithSort, WithDestroy;

    public function render()
    {
        return view('livewire.produk-index', [
            "produks" => Produk::query()
                ->withQuantityInHand()
                ->filterBy($this->filter, ["kode", "nama"])
                ->sortBy($this->sortBy, $this->sortDirection, "nama")
                ->paginate()
        ]);
    }
}
