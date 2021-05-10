<x-layouts.print :title="__('application.sales-return') . ' ' . $returPenjualan->getKey()">
    @foreach ($itemReturPenjualanPages as $pageIndex => $itemReturPenjualans)
        <x-print-sheet>
            <h1 style="font-size: 18pt; text-align: center">
                RETUR PENJUALAN
            </h1>

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
                        <th class="numeric"> Jumlah Harga Rp. </th>
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
                            <td style="text-align: right"> {{ \App\Support\Formatter::currency($itemReturPenjualan->mutasiStockPenjualan->itemFakturPenjualan->jumlah_harga_per_baris) }} </td> {{-- Jumlah Harga Rp.--}}
                        </tr>
                    @endforeach
                </tbody>

                @if($loop->last)
                    <tr>
                        <td colspan="7">  </td> {{-- KODE--}}
                        {{-- Nama Barang--}}
                        {{-- No. Batch--}}
                        {{-- E.D.--}}
                        {{-- Satuan--}}
                        {{-- Quantity--}}
                        {{-- Harga Satuan--}}
                        <td style="text-align: right"> {{ \App\Support\Formatter::currency($total_harga ?? 0) }} </td> {{-- Jumlah Harga Rp.--}}
                    </tr>
                @endif
            </table>
        </x-print-sheet>
    @endforeach
</x-layouts.print>