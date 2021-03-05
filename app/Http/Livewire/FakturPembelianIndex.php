<?php

namespace App\Http\Livewire;

use App\Models\FakturPembelian;
use App\Support\WithCustomPagination;
use App\Support\WithDestroy;
use App\Support\WithFilter;
use App\Support\WithSort;
use Livewire\Component;

class FakturPembelianIndex extends Component
{
    use WithFilter, WithCustomPagination, WithSort, WithDestroy;

    public function render()
    {
        return view('livewire.faktur-pembelian-index', [
            "faktur_pembelians" => FakturPembelian::query()
                ->filterBy($this->filter, ["kode", "pemasok"])
                ->sortBy($this->sortBy, $this->sortDirection, "pemasok")
                ->paginate()
        ]);
    }
}
