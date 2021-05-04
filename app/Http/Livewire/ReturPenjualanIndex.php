<?php

namespace App\Http\Livewire;

use App\Models\ReturPenjualan;
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
        return view('livewire.retur-penjualan-index', [
            "returPenjualans" => ReturPenjualan::query()
                ->orderByDesc("waktu_pengembalian")
                ->paginate(),
        ]);
    }
}
