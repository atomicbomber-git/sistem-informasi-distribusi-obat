<?php

namespace App\Http\Livewire;

use App\Casters\AuditableCaster;
use App\Models\FakturPembelian;
use App\Models\FakturPenjualan;
use App\Models\ItemFakturPembelian;
use App\Models\ItemFakturPenjualan;
use App\Models\ItemReturPembelian;
use App\Models\ItemReturPenjualan;
use App\Models\ReturPembelian;
use App\Models\ReturPenjualan;
use App\Support\WithCustomPagination;
use Livewire\Component;
use OwenIt\Auditing\Models\Audit;

class AuditIndex extends Component
{
    use WithCustomPagination;

    public function render()
    {
        return view('livewire.audit-index', [
            "audits" => Audit::query()
                ->withCasts([
                    "old_values" => AuditableCaster::class,
                    "new_values" => AuditableCaster::class,
                ])
                ->whereIn("auditable_type", array_keys($this->getAuditableClassNames()))
                ->orderByDesc("created_at")
                ->orderByDesc("auditable_type")
                ->orderByRaw("(new_values = '[]')")
                ->paginate()
        ]);
    }

    public function getAuditableClassNames(): array
    {
        return [
            FakturPenjualan::class => __("application.sales_invoice"),
            ItemFakturPenjualan::class => "Item Faktur Penjualan",
            FakturPembelian::class => __("application.purchase_invoice"),
            ItemFakturPembelian::class => "Item Faktur Pembelian",
            ReturPembelian::class => __("application.purchase-return"),
            ItemReturPembelian::class => "Item Retur Pembelian",
            ReturPenjualan::class => __("application.sales-return"),
            ItemReturPenjualan::class => "Item Retur Penjualan",
        ];
    }
}
