<x-layouts.print :title="__('application.purchase_invoice') . ' ' . $fakturPembelian->getKey()">
    @foreach ($itemFakturPembelianPages as $pageIndex => $itemFakturPembelians)
        <x-print-sheet>
            <h1 style="font-size: 18pt; text-align: center">
                FAKTUR PENERIMAAN
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
                    @foreach ($itemFakturPembelians as $itemFakturPembelian)
                        <tr>
                            <td> {{ $itemFakturPembelian->produk_kode }} </td> {{-- KODE--}}
                            <td> {{ $itemFakturPembelian->produk->nama }} </td> {{-- Nama Barang--}}
                            <td> {{ $itemFakturPembelian->kode_batch }} </td> {{-- No. Batch--}}
                            <td> {{ \App\Support\Formatter::dayMonthYear($itemFakturPembelian->expired_at) }} </td> {{-- E.D.--}}
                            <td> {{ $itemFakturPembelian->produk->satuan }} </td> {{-- Satuan--}}
                            <td style="text-align: right"> {{ \App\Support\Formatter::currency($itemFakturPembelian->jumlah) }} </td> {{-- Quantity--}}
                            <td style="text-align: right"> {{ \App\Support\Formatter::currency($itemFakturPembelian->harga_satuan) }} </td> {{-- Harga Satuan--}}
                            <td style="text-align: right"> {{ \App\Support\Formatter::currency($itemFakturPembelian->jumlah_harga_per_baris) }} </td> {{-- Jumlah Harga Rp.--}}
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
                        <td style="text-align: right"> {{ \App\Support\Formatter::currency($total_harga) }} </td> {{-- Jumlah Harga Rp.--}}
                    </tr>
                @endif
            </table>
        </x-print-sheet>
    @endforeach
</x-layouts.print>