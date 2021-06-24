@props([
    "itemFakturPenjualan" => new \App\Models\ItemFakturPenjualan()
])

<dl>
    @if($itemFakturPenjualan->fakturPenjualan)
        <dt> @lang("application.sales_invoice_code") </dt>
        <dd> {{ $itemFakturPenjualan->fakturPenjualan->getPrefixedNomor() }} </dd>
    @endif

    @if($itemFakturPenjualan->produk)
        <dt> @lang("application.product_name") </dt>
        <dd> {{ $itemFakturPenjualan->produk->nama }} </dd>
    @endif

    @if($itemFakturPenjualan->jumlah)
        <dt> @lang("application.quantity") </dt>
        <dd> {{ \App\Support\Formatter::quantity($itemFakturPenjualan->jumlah) }} </dd>
    @endif

    @if($itemFakturPenjualan->harga_satuan)
        <dt> @lang("application.unit_price") </dt>
        <dd> {{ \App\Support\Formatter::currency($itemFakturPenjualan->harga_satuan) }} </dd>
    @endif

    @if($itemFakturPenjualan->diskon)
        <dt> @lang("application.discount_percentage") </dt>
        <dd> {{ \App\Support\Formatter::percentage($itemFakturPenjualan->diskon) }} </dd>
    @endif
</dl>