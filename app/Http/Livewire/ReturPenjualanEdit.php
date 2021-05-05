<?php

namespace App\Http\Livewire;

use App\Models\ItemReturPenjualan;
use App\Models\ReturPenjualan;
use App\Models\Stock;
use App\Rules\ReturPenjualanNomorUnique;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ReturPenjualanEdit extends Component
{
    public ReturPenjualan $returPenjualan;

    /** @var \Illuminate\Support\Collection|\App\Models\ItemReturPenjualan[] $itemReturPenjualans */
    public Collection $itemReturPenjualans;

    public function rules(): array
    {
        return [
            "returPenjualan.nomor" => ["required", "integer", new ReturPenjualanNomorUnique($this->returPenjualan)],
            "returPenjualan.waktu_pengembalian" => ["required", "date_format:Y-m-d\TH:i"],
            "itemReturPenjualans.*.stock_id" => ["required", Rule::exists(Stock::class, "id")],
            "itemReturPenjualans.*.jumlah" => ["required", "numeric", "gt:0"],
            "itemReturPenjualans.*.alasan" => ["required", "string", Rule::in(ItemReturPenjualan::REASONS)],
        ];
    }

    public function mount(): void
    {
        $this->itemReturPenjualans = $this->returPenjualan
            ->itemReturPenjualans()
            ->get();
    }

    public function removeItem(mixed $key)
    {
        if ($this->itemReturPenjualans->has($key)) {
            $this->itemReturPenjualans->forget($key);
        }
    }

    public function render()
    {
        $this->itemReturPenjualans =
            $this->itemReturPenjualans->reject(fn(mixed $item) => !($item instanceof ItemReturPenjualan));

        return view('livewire.retur-penjualan-edit');
    }
}
