<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
    >
    <meta http-equiv="X-UA-Compatible"
          content="ie=edge"
    >
    <title> Cetak Faktur Penjualan {{ $fakturPenjualan->getNomor() }} </title>
    <link rel="stylesheet"
          href="{{ asset("css/paper.css") }}"
    >
    <style>@page { size: A5 landscape }</style>
    <style>
        * {
            font-size: 8.2pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table td.numeric, table th.numeric {
            text-align: end;
        }

        table td, table th {
            text-align: center;
            border: thin solid black;
            padding: 0.2rem;
        }

        header table {
            border: none;
        }

        header table td, header table th {
            text-align: left;
            border: none;
            padding: 0;
        }
    </style>
</head>

<body class="A5 landscape">

@foreach ($mutasiStockPages as $pageIndex => $mutasiStocks)
    <section class="sheet" style="padding: 3mm">
        <header>
            <div style="display: flex">
                <div style="flex: 1; border: thin solid black; padding: 1rem">
                    <div style="text-align: center"> PT KUBURAYA MEDIFARMA </div>
                    <table>
                        <tbody>
                            <tr> <td style="white-space: nowrap; text-align: left" > Izin PBF </td> <td> : </td> <td> HK.07.01/V/387/14 </td> </tr>
                            <tr> <td style="white-space: nowrap; text-align: left" > Izin PAK </td> <td> : </td> <td> HK.07.ALKES/IV/428/AK-2/2011 </td> </tr>
                            <tr> <td style="white-space: nowrap; text-align: left" > Alamat </td> <td> : </td> <td> Jl. Sei Raya Dalama Kom. Ruko Taman Sei Raya No. R3 Kab. Kubu Raya Telp. 0561-710377 / Fax. 0561-711355 </td> </tr>
                            <tr> <td style="white-space: nowrap; text-align: left" > NPWP </td> <td> : </td> <td> 02.904.283.5-701.000 </td> </tr>
                            <tr> <td style="white-space: nowrap; text-align: left" > Email </td> <td> : </td> <td> kumedfar@yahoo.co.id </td> </tr>
                        </tbody>
                    </table>
                </div>
                <div style="flex: 1; text-align: center; display: flex; flex-direction: column; justify-content: space-between">
                    <div></div>
                    <div style="font-size: 18pt"> FAKTUR </div>
                </div>
                <div style="flex: 1; border: thin solid black; display: flex; flex-direction: column; justify-content: space-between; text-align: left">
                    <div> Kepada Yth: </div>
                    <div> NPWP: </div>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; margin-top: 10px; margin-bottom: 10px">
                <div> Via: {{ $fakturPenjualan->pelanggan->nama }} </div>
                <div> No Faktur: {{ $fakturPenjualan->getNomor() }} </div>
                <div> Tgl. Faktur: {{ \App\Support\Formatter::dayMonthYear($fakturPenjualan->waktu_pengeluaran) }} </div>
            </div>
        </header>

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
                <th class="numeric" style="width: 50px"> TD% </th>
                <th class="numeric" style="width: 50px"> CD% </th>
                <th class="numeric"> Jumlah Harga Rp. </th>
            </tr>
            </thead>
            <tbody>
            @foreach ($mutasiStocks as $pageIndex => $mutasiStock)
                <tr>
                    <td> {{ $mutasiStock->itemFakturPenjualan->produk_kode }} </td>
                    <td> {{ $mutasiStock->itemFakturPenjualan->produk->nama }} </td>
                    <td> {{ $mutasiStock->stock->kode_batch }} </td>
                    <td> {{ \App\Support\Formatter::dayMonthYear($mutasiStock->stock->expired_at) }} </td>
                    <td> BOX </td>
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
                        <td style="border: none">  </td>  {{-- KODE --}}
                        <td style="border: none"> Materai Rp. </td>  {{-- Nama Barang --}}
                        <td style="border: none">  </td>  {{-- No. Batch --}}
                        <td colspan="4" class="numeric"> JML. HARGA Rp. {{ \App\Support\Formatter::currency($jumlahHargaTanpaDiskonTanpaPajak) }} </td>  {{-- E.D. --}}
                        {{-- MERGED WITH PREV CELL | Satuan --}}
                        {{-- MERGED WITH PREV CELL | Quantity --}}
                        {{-- MERGED WITH PREV CELL | Harga Satuan --}}
                        <td class="numeric"> DISC I <br/> {{ \App\Support\Formatter::currency($totalDiskonTdAtauDiskonSatu) }} </td> {{-- TD% --}}
                        <td class="numeric"> DISC II <br/> {{ \App\Support\Formatter::currency($totalDiskonCdAtauDiskonDua) }} </td> {{-- CD% --}}
                        <td class="numeric">
                            TOTAL Rp. <br/>
                            {{ \App\Support\Formatter::currency($jumlahHargaDenganDiskonDanPajak) }}
                        </td> {{-- Jumlah Harga Rp. --}}
                    </tr>
                </tfoot>
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
            </div>
            <div style="flex: 1; text-align: center; padding: 0 1rem 0 1rem">
                <br>
                <br>
                Hormat Kami <br>
                <br>
                <br>
                <br>
                <br>
                TEGUH SAPUTRA
                <div style="border-top: thin solid black">
                    19660719/SIKA_61.12/2018/1056
                </div>
            </div>
        </div>
    </section>
@endforeach

</body>
</html>