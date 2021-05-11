<x-layouts.print :title="__('application.purchase-return') . ' ' . $returPembelian->getPrefixedNomor()">
    @foreach ($itemReturPembelianPages as $pageIndex => $itemReturPembelians)
        <x-print-sheet style="display: flex; flex-direction: column ">
            <x-print-header
                    title="RETUR PEMBELIAN"
                    :target="$returPembelian->fakturPembelian->pemasok->nama"
            />

            <div style="display: flex; justify-content: space-between; margin-top: 10px; margin-bottom: 10px">
                <div> Via: {{ $returPembelian->fakturPembelian->pemasok->nama }} </div>
                <div> No Faktur: {{ $returPembelian->getPrefixedNomor() }} </div>
                <div> Tgl. Faktur: {{ \App\Support\Formatter::dayMonthYear($returPembelian->waktu_pengembalian) }} </div>
            </div>

            <table style="flex: 1">
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
                    @foreach ($itemReturPembelians as $itemReturPembelian)
                        <tr>
                            <td> {{ $itemReturPembelian->itemFakturPembelian->produk_kode }} </td> {{-- KODE--}}
                            <td> {{ $itemReturPembelian->itemFakturPembelian->produk->nama }} </td> {{-- Nama Barang--}}
                            <td> {{ $itemReturPembelian->itemFakturPembelian->kode_batch }} </td> {{-- No. Batch--}}
                            <td> {{ \App\Support\Formatter::dayMonthYear($itemReturPembelian->itemFakturPembelian->expired_at) }} </td> {{-- E.D.--}}
                            <td> {{ $itemReturPembelian->itemFakturPembelian->produk->satuan }} </td> {{-- Satuan--}}
                            <td style="text-align: right"> {{ \App\Support\Formatter::currency($itemReturPembelian->jumlah) }} </td> {{-- Quantity--}}
                            <td style="text-align: right"> {{ \App\Support\Formatter::currency($itemReturPembelian->itemFakturPembelian->harga_satuan) }} </td> {{-- Harga Satuan--}}
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <x-print-footer
                :hasLeftSide="false"
            />

        </x-print-sheet>
    @endforeach
</x-layouts.print>