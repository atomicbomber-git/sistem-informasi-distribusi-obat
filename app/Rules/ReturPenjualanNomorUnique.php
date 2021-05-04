<?php

namespace App\Rules;

use App\Models\FakturPenjualan;
use App\Models\ReturPenjualan;
use Illuminate\Contracts\Validation\Rule;
use Jenssegers\Date\Date;

class ReturPenjualanNomorUnique implements Rule
{
    public ReturPenjualan $returPenjualan;
    private ?int $ignore;

    /**
     * Create a new rule instance.
     *
     * @param FakturPenjualan $returPenjualan
     * @param int $ignore
     */
    public function __construct(ReturPenjualan $returPenjualan, $ignore = null)
    {
        $this->returPenjualan = $returPenjualan;
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
        $waktuPengembalian = Date::make($this->returPenjualan->waktu_pengembalian);

        return ReturPenjualan::query()
            ->where("nomor", "=", $value)
            ->where("nomor", "<>", $this->ignore)
            ->whereYear("waktu_pengembalian", $waktuPengembalian->year)
            ->whereMonth("waktu_pengembalian", $waktuPengembalian->month)
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
