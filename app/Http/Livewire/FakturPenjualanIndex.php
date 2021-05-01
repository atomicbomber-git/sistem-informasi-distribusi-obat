<?php

namespace App\Http\Livewire;

use App\Enums\MessageState;
use App\Exceptions\ApplicationException;
use App\Models\FakturPembelian;
use App\Models\FakturPenjualan;
use App\Models\Pelanggan;
use App\QueryBuilders\FakturPenjualanBuilder;
use App\Support\SessionHelper;
use App\Support\WithCustomPagination;
use App\Support\WithDestroy;
use App\Support\WithFilter;
use App\Support\WithSort;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FakturPenjualanIndex extends Component
{
    use WithCustomPagination, WithSort, WithFilter, WithDestroy;

    public mixed $pelangganId = null;

    public function destroy(mixed $modelKey)
    {
        try {
            DB::beginTransaction();

            $fakturPenjualan = FakturPenjualan::query()->findOrFail($modelKey);

            foreach ($fakturPenjualan->itemFakturPenjualans as $itemFakturPenjualan) {
                $itemFakturPenjualan->abortIfUnmodifiable();
                $itemFakturPenjualan->rollbackStockTransaction();
                $itemFakturPenjualan->forceDelete();
            }

            $fakturPenjualan->forceDelete();

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
        return view('livewire.faktur-penjualan-index', [
            "fakturPenjualans" => FakturPenjualan::query()
                ->when($this->pelangganId, function (FakturPenjualanBuilder $builder) {
                    $builder->where("pelanggan_id", $this->pelangganId);
                })
                ->filterBy($this->filter, ["nomor", "pelanggan.nama"])
                ->orderByDesc("waktu_pengeluaran")
                ->paginate()
        ]);
    }
}
