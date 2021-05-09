<?php

namespace App\Http\Livewire;

use App\Models\FakturPembelian;
use App\Models\ReturPembelian;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ReturPembelianCreate extends Component
{
    public ReturPembelian $returPembelian;

    public function rules()
    {
        return [
            "returPembelian.faktur_pembelian_kode" => ["required", Rule::exists(FakturPembelian::class, "kode")],
        ];
    }

    public function mount()
    {
        $this->returPembelian = new ReturPembelian();
    }

    public function render()
    {
        return view('livewire.retur-pembelian-create');
    }
}
