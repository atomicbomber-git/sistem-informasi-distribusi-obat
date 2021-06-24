@props([
    "itemReturPembelian" => new \App\Models\ItemReturPembelian()
])

<dl>
    @if($itemReturPembelian->returPembelian)
        <dt> @lang("application.purchase-return-number") </dt>
        <dd> {{ $itemReturPembelian->returPembelian->nomor }} </dd>
    @endif
    
    @if($itemReturPembelian->itemFakturPembelian)
        <dt> @lang("application.product_name") </dt>
        <dd> {{ $itemReturPembelian->itemFakturPembelian->produk->nama }} </dd>
    @endif

    @if($itemReturPembelian->itemFakturPembelian)
        <dt> @lang("application.batch_code") </dt>
        <dd> {{ $itemReturPembelian->itemFakturPembelian->kode_batch }} </dd>
    @endif

    @if($itemReturPembelian->alasan)
        <dt> @lang("application.reason") </dt>
        <dd> {{ __("application.{$itemReturPembelian->alasan}") }} </dd>
    @endif
</dl>