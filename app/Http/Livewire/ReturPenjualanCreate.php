<?php

namespace App\Http\Livewire;

use App\Models\FakturPenjualan;
use App\Models\MutasiStock;
use App\Models\ReturPenjualan;
use App\Support\HasValidatorThatEmitsErrors;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ReturPenjualanCreate extends Component
{
    use HasValidatorThatEmitsErrors;

    const DAMAGED = "damaged";
    const EXPIRED = "expired";

    const REASONS = [
        self::DAMAGED,
        self::EXPIRED,
    ];

    public ?int $faktur_penjualan_id = null;
    public ReturPenjualan $returPenjualan;

    /** @var array */
    public array $draftItemReturPenjualans;


    public function rules()
    {
        return [
            "returPenjualan.waktu_pengembalian" => ["required", "date_format:Y-m-d\TH:i"],
            "draftItemReturPenjualans" => ["array", "required"],
            "draftItemReturPenjualans.*.mutasiStock.stock.produk_kode" => ["required"],
            "draftItemReturPenjualans.*.mutasiStock.stock.kode_batch" => ["required"],
            "draftItemReturPenjualans.*.jumlah" => ["required", "numeric", "gt:0"],
            "draftItemReturPenjualans.*.alasan" => ["required", "string", Rule::in(self::REASONS)],
        ];
    }

    public function submit()
    {
        $validatedData = $this->validateAndEmitErrors();
    }

    public function addItem(mixed $key)
    {
        $mutasiStock = MutasiStock::query()
            ->with("stock.produk")
            ->findOrFail($key);

        $this->draftItemReturPenjualans[] = [
            "mutasiStock" => $mutasiStock->toArray(),
            "jumlah" => 0,
            "alasan" => self::EXPIRED,
        ];
    }

    public function removeItem(mixed $key)
    {
        if (isset($this->draftItemReturPenjualans[$key])) {
            unset($this->draftItemReturPenjualans[$key]);
        }
    }

    public function emitFakturPenjualanChangedEvent()
    {
        $this->emit(
            "fakturPenjualanChanged",
            route("faktur-penjualan.search-item", FakturPenjualan::find($this->faktur_penjualan_id)),
        );
    }

    public function updated($attribute, $value)
    {
        if ($attribute === "faktur_penjualan_id") {
            $this->emitFakturPenjualanChangedEvent();
        }
    }

    public function mount()
    {
        $this->draftItemReturPenjualans = [];
        $this->returPenjualan = new ReturPenjualan();
    }

    public function render()
    {
        return view('livewire.retur-penjualan-create');
    }
}
