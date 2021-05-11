<x-layouts.print :title="__('application.purchase_invoice') . ' ' . $fakturPembelian->getKey()">
    @foreach ($itemFakturPembelianPages as $pageIndex => $itemFakturPembelians)
        <x-print-sheet style="display: flex; flex-direction: column">
            <h1 style="font-size: 18pt; text-align: center">
                FAKTUR PEMBELIAN
            </h1>

            <div style="display: flex; justify-content: space-between; margin-top: 10px; margin-bottom: 10px">
                <div> Via: {{ $fakturPembelian->pemasok->nama }} </div>
                <div> No Faktur: {{ $fakturPembelian->getKey() }} </div>
                <div> Tgl. Faktur: {{ \App\Support\Formatter::dayMonthYear($fakturPembelian->waktu_penerimaan) }} </div>
            </div>

            <table style="flex: 1">
                <thead>
                <tr>
                    <th> KODE</th>
                    <th> Nama Barang</th>
                    <th> No. Batch</th>
                    <th> E.D.</th>
                    <th> Satuan</th>
                    <th class="numeric"> Quantity</th>
                    <th class="numeric"> Harga Satuan</th>
                    <th class="numeric"> Jumlah Harga Rp.</th>
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
                        <td colspan="7"></td> {{-- KODE--}}
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

            <div style="display: flex; margin-top: 20px">
                <div style="flex: 1; text-align: center">
                    <br>
                    <br>
                    Tanda Terima <br>
                    <br>
                    <br>
                    <br>
                    <pre> (                ) </pre>
                    Nama Jelas
                </div>
                <div style="flex: 1">
                </div>
                <div style="flex: 1; text-align: center; padding: 0 1rem 0 1rem">
                    <br>
                    <br>
                    Hormat Kami <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <div style="border-top: thin solid black; text-transform: uppercase">
                        Admin PT. Kuburaya Mediafarma
                    </div>
                </div>
            </div>


        </x-print-sheet>
    @endforeach
</x-layouts.print>