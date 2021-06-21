@props([
    "fakturPembelian" => new \App\Models\FakturPembelian()
])

<dl>
    @if($fakturPembelian->kode)
        <dt> @lang("application.code") </dt>
        <dd> {{ $fakturPembelian->kode }} </dd>
    @endif

    @if($fakturPembelian->pemasok)
        <dt> @lang("application.supplier") </dt>
        <dd> {{ $fakturPembelian->pemasok?->nama }} </dd>
    @endif

    @if($fakturPembelian->waktu_penerimaan)
        <dt> @lang("application.arrived_at") </dt>
        <dd> {{ \App\Support\Formatter::normalDate($fakturPembelian->waktu_penerimaan) }} </dd>
    @endif
</dl>