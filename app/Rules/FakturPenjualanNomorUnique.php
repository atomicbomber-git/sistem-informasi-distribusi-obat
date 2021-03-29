<?php

namespace App\Rules;

use App\Models\FakturPenjualan;
use Illuminate\Contracts\Validation\Rule;
use Jenssegers\Date\Date;

class FakturPenjualanNomorUnique implements Rule
{
    public FakturPenjualan $fakturPenjualan;
    private ?int $ignore;

    /**
     * Create a new rule instance.
     *
     * @param FakturPenjualan $fakturPenjualan
     * @param int $ignore
     */
    public function __construct(FakturPenjualan $fakturPenjualan, $ignore = null)
    {
        $this->fakturPenjualan = $fakturPenjualan;
        $this->ignore = $ignore;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $waktuPengeluaran = Date::make($this->fakturPenjualan->waktu_pengeluaran);

        return FakturPenjualan::query()
            ->where("nomor", "=", $value)
            ->where("nomor", "<>", $this->ignore)
            ->whereYear("waktu_pengeluaran", $waktuPengeluaran->year)
            ->whereMonth("waktu_pengeluaran", $waktuPengeluaran->month)
            ->doesntExist();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Nomor harus unik untuk setiap bulan';
    }
}
