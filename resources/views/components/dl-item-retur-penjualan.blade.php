@props([
    "itemReturPenjualan" => new \App\Models\ItemReturPenjualan()
])

<dl>
    @if($itemReturPenjualan->returPenjualan)
        <dt> @lang("application.sales-return-number") </dt>
        <dd> {{ $itemReturPenjualan->returPenjualan->nomor }} </dd>
    @endif

    @if($itemReturPenjualan->mutasiStockPenjualan)
        <dt> @lang("application.product_name") </dt>
        <dd> {{ $itemReturPenjualan->mutasiStockPenjualan->itemFakturPenjualan->produk->nama }} </dd>
    @endif

    @if($itemReturPenjualan->mutasiStockPenjualan)
        <dt> @lang("application.batch_code") </dt>
        <dd> {{ $itemReturPenjualan->mutasiStockPenjualan->stock->kode_batch }} </dd>
    @endif

    @if($itemReturPenjualan->jumlah)
        <dt> @lang("application.quantity") </dt>
        <dd> {{ $itemReturPenjualan->jumlah }} </dd>
    @endif

    @if($itemReturPenjualan->alasan)
        <dt> @lang("application.reason") </dt>
        <dd> {{ __("application.{$itemReturPenjualan->alasan}") }} </dd>
    @endif
</dl>