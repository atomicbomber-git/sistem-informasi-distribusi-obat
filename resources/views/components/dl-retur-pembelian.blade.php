@props([
    "returPembelian" => new \App\Models\ReturPembelian()
])

<dl>
    @if($returPembelian->nomor)
        <dt> @lang("application.number") </dt>
        <dd> {{ $returPembelian->nomor }} </dd>
    @endif

    @if($returPembelian->fakturPembelian)
        <dt> @lang("application.purchase_invoice_code") </dt>
        <dd> {{ $returPembelian->faktur_pembelian_kode }} </dd>
    @endif

    @if($returPembelian->waktu_pengembalian)
        <dt> @lang("application.returned_at") </dt>
        <dd> {{ \App\Support\Formatter::normalDate($returPembelian->waktu_pengembalian) }} </dd>
    @endif
</dl>