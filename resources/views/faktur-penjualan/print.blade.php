<x-layouts.print :title="__('application.sales_invoice') . ' ' . $fakturPenjualan->getPrefixedNomor()">
    @foreach ($mutasiStockPages as $pageIndex => $mutasiStocks)
        <x-print-sheet style="display: flex; flex-direction: column">
            <x-print-header
                    :faktur-penjualan="$fakturPenjualan"
                    :target="$fakturPenjualan->pelanggan->nama"
            />

            <div style="display: flex; justify-content: space-between; margin-top: 10px; margin-bottom: 10px">
                <div> Via: {{ $fakturPenjualan->pelanggan->nama }} </div>
                <div> No Faktur: {{ $fakturPenjualan->getPrefixedNomor() }} </div>
                <div> Tgl. Faktur: {{ \App\Support\Formatter::dayMonthYear($fakturPenjualan->waktu_pengeluaran) }} </div>
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
                    <th class="numeric"
                        style="width: 50px"
                    > TD%
                    </th>
                    <th class="numeric"
                        style="width: 50px"
                    > CD%
                    </th>
                    <th class="numeric"> Jumlah Harga Rp.</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($mutasiStocks as $pageIndex => $mutasiStock)
                    <tr>
                        <td> {{ $mutasiStock->itemFakturPenjualan->produk_kode }} </td>
                        <td> {{ $mutasiStock->itemFakturPenjualan->produk->nama }} </td>
                        <td> {{ $mutasiStock->stock->kode_batch }} </td>
                        <td> {{ \App\Support\Formatter::dayMonthYear($mutasiStock->stock->expired_at) }} </td>
                        <td> {{ $mutasiStock->itemFakturPenjualan->produk->satuan }} </td>
                        <td class="numeric"> {{ \App\Support\Formatter::quantity(abs($mutasiStock->jumlah)) }} </td>
                        <td class="numeric"> {{ \App\Support\Formatter::currency($mutasiStock->itemFakturPenjualan->harga_satuan) }} </td>
                        <td class="numeric"> {{ \App\Support\Formatter::percentage($mutasiStock->itemFakturPenjualan->diskon)  }} </td>
                        <td class="numeric"> {{ \App\Support\Formatter::percentage($fakturPenjualan->diskon) }} </td>
                        <td class="numeric"> {{ \App\Support\Formatter::currency( $mutasiStock->jumlah_harga_per_baris ) }} </td>
                    </tr>
                @endforeach
                </tbody>

                @if($loop->last)
                    <tfoot>
                    <tr>
                        <td style="border: none"></td> {{-- KODE --}}
                        <td style="border: none"> Materai Rp.</td> {{-- Nama Barang --}}
                        <td style="border: none"></td> {{-- No. Batch --}}
                        <td colspan="4"
                            class="numeric"
                        > JML. HARGA
                            Rp. {{ \App\Support\Formatter::currency($jumlahHargaTanpaDiskonTanpaPajak) }} </td> {{-- E.D. --}}
                        {{-- MERGED WITH PREV CELL | Satuan --}}
                        {{-- MERGED WITH PREV CELL | Quantity --}}
                        {{-- MERGED WITH PREV CELL | Harga Satuan --}}
                        <td class="numeric"> DISC I
                            <br/> {{ \App\Support\Formatter::currency($totalDiskonTdAtauDiskonSatu) }}
                        </td> {{-- TD% --}}
                        <td class="numeric"> DISC II
                            <br/> {{ \App\Support\Formatter::currency($totalDiskonCdAtauDiskonDua) }}
                        </td> {{-- CD% --}}
                        <td class="numeric">
                            TOTAL Rp. <br/>
                            {{ \App\Support\Formatter::currency($jumlahHargaDenganDiskonDanPajak) }}
                        </td> {{-- Jumlah Harga Rp. --}}
                    </tr>
                    </tfoot>
                @endif
            </table>
            <x-print-footer>
                <div style="border: thin solid black">
                    <ul style="list-style-type: '-'; list-style-position: inside; padding-left: 0; margin: 0">
                        <li> Barang-barang tersebut telah diterima dengan baik </li>
                        <li> Penagihan hanya dengan faktur asli </li>
                        <li> Pembayaran degan Giro dianggap lunas setelah diuangkan </li>
                        <li> Giro yang ditolak akan dibebankan biaya bank </li>
                        <li> Barang telah dibeli tidak dapat ditukar / dikembalikan </li>
                    </ul>
                </div>

                <div style="border: thin solid black; margin-top: 1rem">
                    Mohon ditransfer ke No. Rekening <br>
                    PT. Kuburaya Medifarma
                    AC. 1460005181321 Bank Mandiri Cabang Ngurah Rai Pontianak
                </div>
            </x-print-footer>
        </x-print-sheet>
    @endforeach
</x-layouts.print>