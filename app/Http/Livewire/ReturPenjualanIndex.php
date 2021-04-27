<?php

namespace App\Http\Livewire;

use App\Support\WithCustomPagination;
use App\Support\WithDestroy;
use App\Support\WithFilter;
use App\Support\WithSort;
use Livewire\Component;

class ReturPenjualanIndex extends Component
{
    use WithFilter, WithCustomPagination, WithSort, WithDestroy;

    public function render()
    {
        return view('livewire.retur-penjualan-index');
    }
}
