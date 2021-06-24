<?php

namespace App\Http\Livewire;

use App\Enums\MessageState;
use App\Models\ItemReturPembelian;
use App\Models\ReturPembelian;
use App\Support\SessionHelper;
use App\Support\WithCustomPagination;
use App\Support\WithDateFilter;
use App\Support\WithDestroy;
use App\Support\WithSort;
use App\Support\WithTextFilter;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class ReturPembelianIndex extends Component
{
    use WithTextFilter, WithDateFilter, WithCustomPagination, WithSort, WithDestroy;

    public $pemasok_id = null;

    public function updatedPemasokId()
    {
        $this->resetPage();
    }

    public function destroy(mixed $key): void
    {
        try {
            DB::beginTransaction();

            $returPembelian = ReturPembelian::findOrFail($key);

            $returPembelian->itemReturPembelians->each(function (ItemReturPembelian $itemReturPembelian) {
                $itemReturPembelian->rollbackStockTransaction();
                $itemReturPembelian->forceDelete();
            });

            $returPembelian->forceDelete();

            DB::commit();

            SessionHelper::flashMessage(
                __("messages.delete.success"),
                MessageState::STATE_SUCCESS,
            );
        } catch (Exception $exception) {
            report($exception);
            SessionHelper::flashMessage(
                __("messages.delete.failure"),
                MessageState::STATE_DANGER,
            );
        }
    }

    public function render()
    {
        return view('livewire.retur-pembelian-index', [
            "returPembelians" => ReturPembelian::query()
                ->when($this->date_filter_begin, function ($builder, string $date_filter_begin) {
                    $builder->whereDate("waktu_pengembalian", ">=", $date_filter_begin);
                })
                ->when($this->date_filter_end, function ($builder, string $date_filter_end) {
                    $builder->whereDate("waktu_pengembalian", "<=", $date_filter_end);
                })
                ->when($this->pemasok_id, function (Builder $builder) {
                    $builder->whereHas("fakturPembelian", function (Builder $builder) {
                        $builder->where("pemasok_id", $this->pemasok_id);
                    });
                })
                ->paginate()
        ]);
    }
}
