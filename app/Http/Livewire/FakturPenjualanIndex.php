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
    use WithCustomPagination, WithSort, WithFilter, WithDestroy;

    public function render()
    {
        return view('livewire.faktur-penjualan-index', [
            "fakturPenjualans" => FakturPenjualan::query()
                ->paginate()
        ]);
    }
}
