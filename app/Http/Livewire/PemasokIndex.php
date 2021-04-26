<?php

namespace App\Http\Livewire;

use App\Models\Pelanggan;
use App\Models\Pemasok;
use App\Support\WithCustomPagination;
use App\Support\WithDestroy;
use App\Support\WithFilter;
use App\Support\WithSort;
use Livewire\Component;

class PemasokIndex extends Component
{
    use WithCustomPagination, WithSort, WithFilter, WithDestroy;

    public function render()
    {
        return view('livewire.pemasok-index', [
            "pemasoks" => Pemasok::query()
                ->selectQualify(["id", "nama", "alamat"])
                ->sortBy("nama")
                ->filterBy($this->filter, ["nama"])
                ->paginate()
        ]);
    }
}
