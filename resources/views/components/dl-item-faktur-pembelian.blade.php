@props([
    "itemFakturPembelian" => new \App\Models\ItemFakturPembelian()
])

<dl>
    @if($itemFakturPembelian->faktur_pembelian_kode)
        <dt> Kode Faktur Pembelian </dt>
        <dd> {{ $itemFakturPembelian->faktur_pembelian_kode }} </dd>
    @endif

    @if($itemFakturPembelian->produk)
        <dt> Nama Produk</dt>
        <dd> {{ $itemFakturPembelian->produk?->nama }} </dd>
    @endif

    @if($itemFakturPembelian->kode_batch)
        <dt> Kode Batch</dt>
        <dd> {{ $itemFakturPembelian->kode_batch }} </dd>
    @endif

    @if($itemFakturPembelian->jumlah)
        <dt> Jumlah</dt>
        <dd> {{ $itemFakturPembelian->jumlah }} </dd>
    @endif

    @if($itemFakturPembelian->harga_satuan)
        <dt> Harga Satuan </dt>
        <dd> {{ $itemFakturPembelian->harga_satuan }} </dd>
    @endif

    @if($itemFakturPembelian->expired_at)
        <dt> Expired At </dt>
        <dd> {{ $itemFakturPembelian->expired_at }} </dd>
    @endif
</dl>