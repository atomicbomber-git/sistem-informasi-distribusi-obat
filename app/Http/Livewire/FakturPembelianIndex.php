<?php

namespace App\Http\Livewire;

use App\Enums\MessageState;
use App\Exceptions\ApplicationException;
use App\Models\FakturPembelian;
use App\QueryBuilders\FakturPembelianBuilder;
use App\Support\SessionHelper;
use App\Support\WithCustomPagination;
use App\Support\WithDateFilter;
use App\Support\WithDestroy;
use App\Support\WithSort;
use App\Support\WithTextFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FakturPembelianIndex extends Component
{
    use WithTextFilter, WithCustomPagination, WithSort, WithDateFilter, WithDestroy;

    public $pemasok_id = null;

    public function updatedPemasokId()
    {
        $this->resetPage();
    }

    public function destroy(mixed $modelKey)
    {
        try {
            DB::beginTransaction();

            $fakturPembelian = FakturPembelian::query()->findOrFail($modelKey);

            foreach ($fakturPembelian->item_faktur_pembelians as $item_faktur_pembelian) {
                $item_faktur_pembelian->abortIfUnmodifiable();
                $item_faktur_pembelian->destroyCascade();
            }

            $fakturPembelian->forceDelete();

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
        return view('livewire.faktur-pembelian-index', [
            "faktur_pembelians" => FakturPembelian::query()
                ->selectQualify([
                    "kode",
                    "pemasok_id",
                    "waktu_penerimaan",
                    "created_at",
                    "updated_at",
                ])
                ->with("pemasok")
                ->orderByDesc("waktu_penerimaan")
                ->when($this->date_filter_begin, function (FakturPembelianBuilder $builder, string $date_filter_begin) {
                    $builder->whereDate("waktu_penerimaan", ">=", $date_filter_begin);
                })
                ->when($this->date_filter_end, function (FakturPembelianBuilder $builder, string $date_filter_end) {
                    $builder->whereDate("waktu_penerimaan", "<=", $date_filter_end);
                })

                ->when($this->pemasok_id, function (Builder $builder) {
                    $builder->where("pemasok_id", $this->pemasok_id);
                })
                ->filterBy($this->filter, ["kode", "pemasok.nama"])
                ->sortBy($this->sortBy, $this->sortDirection, "pemasok_id")
                ->paginate()
        ]);
    }
}
