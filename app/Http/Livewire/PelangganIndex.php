<?php

namespace App\Http\Livewire;

use App\Models\Pelanggan;
use App\Support\WithCustomPagination;
use App\Support\WithDestroy;
use App\Support\WithFilter;
use App\Support\WithSort;
use Livewire\Component;

class PelangganIndex extends Component
{
    use WithCustomPagination, WithSort, WithFilter, WithDestroy;

    public function render()
    {
        return view('livewire.pelanggan-index', [
            "pelanggans" => Pelanggan::query()
                ->selectQualify(["id", "nama", "alamat"])
                ->sortBy("nama")
                ->filterBy($this->filter, ["nama", "alamat"])
                ->paginate()
        ]);
    }
}
