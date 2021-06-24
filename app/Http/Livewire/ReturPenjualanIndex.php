<?php

namespace App\Http\Livewire;

use App\Enums\MessageState;
use App\Exceptions\ApplicationException;
use App\Models\ReturPenjualan;
use App\Support\SessionHelper;
use App\Support\WithCustomPagination;
use App\Support\WithDateFilter;
use App\Support\WithDestroy;
use App\Support\WithSort;
use App\Support\WithTextFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ReturPenjualanIndex extends Component
{
    use WithTextFilter, WithDateFilter, WithCustomPagination, WithSort, WithDestroy;

    public $pelanggan_id = null;

    public function updatedPelangganId()
    {
        $this->resetPage();
    }

    public function destroy(mixed $modelKey)
    {
        try {
            DB::beginTransaction();

            $returPenjualan = ReturPenjualan::query()->findOrFail($modelKey);

            foreach ($returPenjualan->itemReturPenjualans as $itemReturPenjualan) {
                $itemReturPenjualan->rollbackStockTransaction();
                $itemReturPenjualan->forceDelete();
            }

            $returPenjualan->forceDelete();

            DB::commit();

            SessionHelper::flashMessage(
                __("messages.delete.success"),
                MessageState::STATE_SUCCESS,
            );
        } catch (ApplicationException $exception) {
            SessionHelper::flashMessage(
                $exception->getMessage(),
                MessageState::STATE_DANGER,
            );
        }
    }

    public function render()
    {
        return view('livewire.retur-penjualan-index', [
            "returPenjualans" => ReturPenjualan::query()
                ->when($this->date_filter_begin, function ($builder, string $date_filter_begin) {
                    $builder->whereDate("waktu_pengembalian", ">=", $date_filter_begin);
                })
                ->when($this->date_filter_end, function ($builder, string $date_filter_end) {
                    $builder->whereDate("waktu_pengembalian", "<=", $date_filter_end);
                })
                ->when($this->pelanggan_id, function (Builder $builder, $pelanggan_id) {
                    $builder->whereHas("fakturPenjualan", function (Builder $builder) {
                        $builder->where("pelanggan_id", $this->pelanggan_id);
                    });
                })
                ->with("fakturPenjualan.pelanggan")
                ->orderByDesc("waktu_pengembalian")
                ->paginate(),
        ]);
    }
}
