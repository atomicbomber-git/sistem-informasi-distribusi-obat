<?php

namespace App\Http\Livewire;

use App\Models\Produk;
use App\Support\WithCustomPagination;
use App\Support\WithDestroy;
use App\Support\WithTextFilter;
use App\Support\WithSort;
use Exception;
use Livewire\Component;

class ProdukIndex extends Component
{
    use WithTextFilter, WithCustomPagination, WithSort, WithDestroy;

    public function deleteFailureMessage(Exception $exception): string
    {
        return __("messages.product.delete.failure");
    }

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
