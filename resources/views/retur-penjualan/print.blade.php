<x-layouts.print :title="__('application.sales-return') . ' ' . $returPenjualan->getPrefixedNomor()">
    @foreach ($itemReturPenjualanPages as $pageIndex => $itemReturPenjualans)
        <x-print-sheet>
            <h1 style="font-size: 18pt; text-align: center">
                RETUR PENJUALAN
            </h1>

            <div style="display: flex; justify-content: space-between; margin-top: 10px; margin-bottom: 10px">
                <div> Via: {{ $returPenjualan->fakturPenjualan->pelanggan->nama }} </div>
                <div> No Faktur: {{ $returPenjualan->getPrefixedNomor() }} </div>
                <div> Tgl. Faktur: {{ \App\Support\Formatter::dayMonthYear($returPenjualan->waktu_pengembalian) }} </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th> KODE </th>
                        <th> Nama Barang </th>
                        <th> No. Batch </th>
                        <th> E.D. </th>
                        <th> Satuan </th>
                        <th class="numeric"> Quantity </th>
                        <th class="numeric"> Harga Satuan </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($itemReturPenjualans as $itemReturPenjualan)
                        <tr>
                            <td> {{ $itemReturPenjualan->mutasiStockPenjualan->itemFakturPenjualan->produk_kode }} </td> {{-- KODE--}}
                            <td> {{ $itemReturPenjualan->mutasiStockPenjualan->itemFakturPenjualan->produk->nama }} </td> {{-- Nama Barang--}}
                            <td> {{ $itemReturPenjualan->mutasiStockPenjualan->stock->kode_batch }} </td> {{-- No. Batch--}}
                            <td> {{ \App\Support\Formatter::dayMonthYear($itemReturPenjualan->mutasiStockPenjualan->stock->expired_at) }} </td> {{-- E.D.--}}
                            <td> {{ $itemReturPenjualan->mutasiStockPenjualan->itemFakturPenjualan->produk->satuan }} </td> {{-- Satuan--}}
                            <td style="text-align: right"> {{ \App\Support\Formatter::currency($itemReturPenjualan->jumlah) }} </td> {{-- Quantity--}}
                            <td style="text-align: right"> {{ \App\Support\Formatter::currency($itemReturPenjualan->mutasiStockPenjualan->itemFakturPenjualan->harga_satuan) }} </td> {{-- Harga Satuan--}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-print-sheet>
    @endforeach
</x-layouts.print>