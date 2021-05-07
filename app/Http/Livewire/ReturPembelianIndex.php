<?php

namespace App\Http\Livewire;

use App\Models\ReturPembelian;
use App\Support\WithCustomPagination;
use App\Support\WithDestroy;
use App\Support\WithFilter;
use App\Support\WithSort;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class ReturPembelianIndex extends Component
{
    use WithFilter, WithCustomPagination, WithSort, WithDestroy;
    public $pemasok_id = null;

    // TODO: DELETE FEATURE

    public function render()
    {
        return view('livewire.retur-pembelian-index', [
            "returPembelians" => ReturPembelian::query()
                ->when($this->pemasok_id, function (Builder $builder) {
                    $builder->whereHas("fakturPembelian", function (Builder $builder) {
                        $builder->where("pemasok_id", $this->pemasok_id);
                    });
                })
                ->paginate()
        ]);
    }
}
