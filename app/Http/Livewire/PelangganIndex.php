<?php

namespace App\Http\Livewire;

use App\Models\Pelanggan;
use App\Support\WithCustomPagination;
use App\Support\WithDestroy;
use App\Support\WithTextFilter;
use App\Support\WithSort;
use Exception;
use Livewire\Component;

class PelangganIndex extends Component
{
    use WithCustomPagination, WithSort, WithTextFilter, WithDestroy;

    public function deleteFailureMessage(Exception $exception): string
    {
        return __("messages.customer.delete.failure");
    }

    public function render()
    {
        return view('livewire.pelanggan-index', [
            "pelanggans" => Pelanggan::query()
                ->selectQualify(["id", "nama", "alamat"])
                ->sortBy("nama")
                ->filterBy($this->filter, ["nama"])
                ->paginate()
        ]);
    }
}
