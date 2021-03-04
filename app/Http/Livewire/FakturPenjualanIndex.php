<?php

namespace App\Http\Livewire;

use App\Models\FakturPenjualan;
use App\Support\WithCustomPagination;
use App\Support\WithDestroy;
use App\Support\WithFilter;
use App\Support\WithSort;
use Livewire\Component;

class FakturPenjualanIndex extends Component
{
    use WithFilter, WithCustomPagination, WithSort, WithDestroy;

    public function render()
    {
        return view('livewire.faktur-penjualan-index', [
            "faktur_penjualans" => FakturPenjualan::query()
                ->filterBy($this->filter, ["kode", "pelanggan"])
                ->sortBy($this->sortBy, $this->sortDirection, "pelanggan")
                ->paginate()
        ]);
    }
}
