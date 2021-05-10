<?php

namespace App\Rules;

use App\Models\FakturPenjualan;
use App\Models\ReturPembelian;
use App\Models\ReturPenjualan;
use Illuminate\Contracts\Validation\Rule;
use Jenssegers\Date\Date;

class ReturPembelianNomorUnique implements Rule
{
    public ReturPembelian $returPembelian;
    private ?int $ignore;

    /**
     * Create a new rule instance.
     *
     * @param ReturPembelian $returPembelian
     * @param int|null $nomorToBeIgnored
     */
    public function __construct(ReturPembelian $returPembelian, int $nomorToBeIgnored = null)
    {
        $this->returPembelian = $returPembelian;
        $this->ignore = $nomorToBeIgnored;
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
        if ( ! $this->returPembelian->waktu_pengembalian) return false;

        $waktuPengembalian = Date::make($this->returPembelian->waktu_pengembalian);

        return ReturPembelian::query()
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
