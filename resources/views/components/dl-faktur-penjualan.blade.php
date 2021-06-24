@props([
    "fakturPenjualan" => new \App\Models\FakturPenjualan()
])

<dl>
    @if($fakturPenjualan->pelanggan)
        <dt> @lang("application.customer") </dt>
        <dd> {{ $fakturPenjualan->pelanggan?->nama }} </dd>
    @endif

    @if($fakturPenjualan->pelanggan)
        <dt> @lang("application.number") </dt>
        <dd> {{ $fakturPenjualan->nomor }} </dd>
    @endif

    @if($fakturPenjualan->diskon)
        <dt> @lang("application.discount_percentage") </dt>
        <dd> {{ \App\Support\Formatter::percentage($fakturPenjualan->diskon) }} </dd>
    @endif

    @if($fakturPenjualan->pajak)
        <dt> @lang("application.tax_percentage") </dt>
        <dd> {{ \App\Support\Formatter::percentage($fakturPenjualan->pajak) }} </dd>
    @endif

    @if($fakturPenjualan->waktu_pengeluaran)
        <dt> @lang("application.sold_at") </dt>
        <dd> {{ \App\Support\Formatter::normalDate($fakturPenjualan->waktu_pengeluaran) }} </dd>
    @endif
</dl>