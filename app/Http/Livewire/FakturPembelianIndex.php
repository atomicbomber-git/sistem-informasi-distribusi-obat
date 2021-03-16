<?php

namespace App\Http\Livewire;

use App\Enums\MessageState;
use App\Models\FakturPembelian;
use App\Support\SessionHelper;
use App\Support\WithCustomPagination;
use App\Support\WithDestroy;
use App\Support\WithFilter;
use App\Support\WithSort;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Throwable;

class FakturPembelianIndex extends Component
{
    use WithFilter, WithCustomPagination, WithSort, WithDestroy;

    public function destroy(mixed $modelKey)
    {
        try {
            DB::beginTransaction();

            $fakturPembelian = FakturPembelian::query()->findOrFail($modelKey);

            foreach ($fakturPembelian->item_faktur_pembelians as $item_faktur_pembelian) {
                $item_faktur_pembelian->deleteCascade();
            }

            $fakturPembelian->delete();

            DB::commit();

            SessionHelper::flashMessage(
                __("messages.delete.success"),
                MessageState::STATE_SUCCESS,
            );
        } catch (Throwable $exception) {
            SessionHelper::flashMessage(
                __("messages.delete.failure"),
                MessageState::STATE_DANGER,
            );
        }
    }

    public function render()
    {
        return view('livewire.faktur-pembelian-index', [
            "faktur_pembelians" => FakturPembelian::query()
                ->filterBy($this->filter, ["kode", "pemasok"])
                ->sortBy($this->sortBy, $this->sortDirection, "pemasok")
                ->paginate()
        ]);
    }
}
