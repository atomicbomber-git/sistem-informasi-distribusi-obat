<?php

namespace App\Http\Livewire;

use App\Models\StockBatch;
use App\Support\WithCustomPagination;
use App\Support\WithDestroy;
use App\Support\WithFilter;
use App\Support\WithSort;
use Livewire\Component;

class StockIndex extends Component
{
    use WithFilter, WithCustomPagination, WithSort;

    public function render()
    {
        return view('livewire.stock-index', [
            "stocks" => StockBatch::query()
                ->paginate()
        ]);
    }
}
