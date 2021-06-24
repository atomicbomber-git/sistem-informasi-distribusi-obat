@props([
    "returPenjualan" => new \App\Models\ReturPenjualan()
])

<dl>
    @if($returPenjualan->nomor)
        <dt> @lang("application.number") </dt>
        <dd> {{ $returPenjualan->nomor }} </dd>
    @endif

    @if($returPenjualan->fakturPenjualan)
        <dt> @lang("application.sales-return-number") </dt>
        <dd> {{ $returPenjualan->fakturPenjualan->nomor }} </dd>
    @endif

    @if($returPenjualan->waktu_pengembalian)
        <dt> @lang("application.returned_at") </dt>
        <dd> {{ $returPenjualan->re }} </dd>
    @endif

    @if($returPenjualan->waktu_pengembalian)
        <dt> @lang("application.returned_at") </dt>
        <dd> {{ \App\Support\Formatter::normalDate($returPenjualan->waktu_pengembalian) }} </dd>
    @endif
</dl>