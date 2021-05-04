<?php

namespace App\Http\Livewire;

use App\Enums\MessageState;
use App\Models\FakturPenjualan;
use App\Models\ItemReturPenjualan;
use App\Models\MutasiStock;
use App\Models\ReturPenjualan;
use App\Models\Stock;
use App\Support\HasValidatorThatEmitsErrors;
use App\Support\SessionHelper;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class ReturPenjualanCreate extends Component
{
    use HasValidatorThatEmitsErrors;

    public ?int $faktur_penjualan_id = null;
    public mixed $waktu_pengembalian = null;

    /** @var array */
    public array $draftItemReturPenjualans;


    public function rules()
    {
        return [
            // TODO: Validate against faktur that already has retur
            "faktur_penjualan_id" => ["bail", "required", Rule::exists(
                FakturPenjualan::class, "id")
                        ->where(function (Builder $query) {
                            ray()->send($query::class);
                        })
            ],

            // TODO: Validate against faktur waktu_pengeluaran
            "waktu_pengembalian" => ["required", "date_format:Y-m-d\TH:i"],

            "draftItemReturPenjualans" => ["array", "required"],
            "draftItemReturPenjualans.*.mutasi_stock_id" => ["bail", "required", Rule::exists(MutasiStock::class, "id")
                ->where(function (Builder &$builder) {
                    $builder = MutasiStock::query()
                        ->whereHas("itemFakturPenjualan.fakturPenjualan", function (EloquentBuilder $builder) {
                            $builder->where("id", $this->faktur_penjualan_id);
                        });
                })],
            "draftItemReturPenjualans.*.nama_produk" => ["required"],
            "draftItemReturPenjualans.*.jumlah_original" => ["required"],
            "draftItemReturPenjualans.*.produk_kode" => ["required"],
            "draftItemReturPenjualans.*.kode_batch" => ["required"],
            "draftItemReturPenjualans.*.jumlah" => [
                "required",
                "numeric",
                "gt:0",
                function ($attribute, $value, $fail)
                {
                    [$arrayName, $index,]  = explode('.', $attribute);
                    $mutasiStockIdAttribute =  "{$arrayName}.{$index}.mutasi_stock_id";

                    $passes = MutasiStock::query()
                        ->whereKey(data_get($this, $mutasiStockIdAttribute))
                        ->whereRaw("-jumlah >= ?", [$value])
                        ->exists();

                    if (!$passes) {
                        $fail("Jumlah harus <= jumlah awal.");
                    }
                },
            ],
            "draftItemReturPenjualans.*.alasan" => ["required", "string", Rule::in(ItemReturPenjualan::REASONS)],
        ];
    }

    public function submit()
    {
        $validatedData = $this->validateAndEmitErrors();

        $this->validateInCaseOfDuplicatedItems($validatedData["draftItemReturPenjualans"]);

        DB::beginTransaction();

        /** @var ReturPenjualan $returPenjualan */
        $returPenjualan = ReturPenjualan::query()->create([
            "faktur_penjualan_id" => $this->faktur_penjualan_id,
            "waktu_pengembalian" => $this->waktu_pengembalian,
        ]);

        foreach ($validatedData["draftItemReturPenjualans"] as $draftItemReturPenjualan) {
            /** @var MutasiStock $mutasiStock */
            $mutasiStock = MutasiStock::query()
                ->findOrFail($draftItemReturPenjualan["mutasi_stock_id"]);

            $itemReturPenjualan = $returPenjualan->itemReturPenjualans()->create([
                "stock_id" => $mutasiStock->stock_id,
                "jumlah" => $draftItemReturPenjualan["jumlah"],
                "alasan" => $draftItemReturPenjualan["alasan"],
            ]);

            $itemReturPenjualan->commitStockTransaction();
        }

        DB::commit();

        SessionHelper::flashMessage(
            __("messages.create.success"),
            MessageState::STATE_SUCCESS,
        );

        $this->redirectRoute("retur-penjualan.index");
    }

    public function addItem(string $key)
    {
        $mutasiStock = MutasiStock::query()
            ->with("stock.produk")
            ->findOrFail($key);

        $this->draftItemReturPenjualans[] = [
            "mutasi_stock_id" => $mutasiStock->id,
            "produk_kode" => $mutasiStock->itemFakturPenjualan->produk_kode,
            "nama_produk" => $mutasiStock->itemFakturPenjualan->produk->nama,
            "jumlah_original" => -$mutasiStock->jumlah,
            "kode_batch" => $mutasiStock->stock->kode_batch,
            "jumlah" => 0,
            "alasan" => ItemReturPenjualan::EXPIRED,
        ];
    }

    public function updated($attribute, $value): void
    {
        if ($attribute === "faktur_penjualan_id") {
            $this->emitFakturPenjualanChangedEvent();
        }
    }

    public function removeItem(string $key)
    {
        unset($this->draftItemReturPenjualans[$key]);
    }

    public function emitFakturPenjualanChangedEvent()
    {
        $this->emit(
            "fakturPenjualanChanged",
            route("faktur-penjualan.search-item", FakturPenjualan::find($this->faktur_penjualan_id)),
        );
    }

    public function mount()
    {
        $this->draftItemReturPenjualans = [];
    }

    public function render()
    {
        $this->pruneInvalidItems();
        return view('livewire.retur-penjualan-create');
    }

    private function pruneInvalidItems(): void
    {
        $this->draftItemReturPenjualans = array_values(
            array_filter(
                $this->draftItemReturPenjualans,
                fn(array $item) => isset($item["nama_produk"]),
            )
        );
    }

    /**
     * @param $draftItemReturPenjualans
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validateInCaseOfDuplicatedItems(array $draftItemReturPenjualans): void
    {
        $errorMessagesForDuplicatedDraftItems = collect($draftItemReturPenjualans)
            ->groupBy(
                fn(array $draftItemReturPenjualan) => $draftItemReturPenjualan["mutasi_stock_id"] . "-" . $draftItemReturPenjualan["alasan"],
                true
            )
            ->filter(fn(Collection $group) => $group->count() > 1)
            ->collapse()
            ->mapWithKeys(fn(array $draftItem, int $index) => [
                "draftItemReturPenjualans.{$index}.jumlah" => "Tidak boleh terdapat item ganda untuk pasangan item- kode batch - alasan",
                "draftItemReturPenjualans.{$index}.alasan" => "Tidak boleh terdapat item ganda untuk pasangan item- kode batch - alasan",
            ]);

        if ($errorMessagesForDuplicatedDraftItems->isNotEmpty() > 0) {
            throw $this->emitErrors(ValidationException::withMessages($errorMessagesForDuplicatedDraftItems->toArray()));
        }
    }
}
