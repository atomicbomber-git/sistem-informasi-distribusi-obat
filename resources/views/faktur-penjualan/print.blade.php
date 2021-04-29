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
            border: thin solid black;
            border-collapse: collapse;
        }

        table td.numeric, table th.numeric {
            text-align: end;
        }

        table td, table th {
            text-align: center;
            border: thin solid black;
            padding: 0.3rem;
        }
    </style>
</head>

<body class="A5 landscape">

@foreach ($mutasiStockPages as $pageIndex => $mutasiStocks)
    <section class="sheet" style="padding: 3mm">
        <header>
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
                    <tr>/
                        <td>  </td>  {{-- KODE --}}
                        <td>  </td>  {{-- Nama Barang --}}
                        <td>  </td>  {{-- No. Batch --}}
                        <td colspan="4" class="numeric"> JML. HARGA Rp. {{ \App\Support\Formatter::currency($jumlahHargaTanpaDiskonTanpaPajak) }} </td>  {{-- E.D. --}}
                        {{-- MERGED WITH PREV CELL | Satuan --}}
                        {{-- MERGED WITH PREV CELL | Quantity --}}
                        {{-- MERGED WITH PREV CELL | Harga Satuan --}}
                        <td class="numeric"> DISC I <br/> {{ \App\Support\Formatter::currency($totalDiskonTdAtauDiskonSatu) }} </td> {{-- TD% --}}
                        <td class="numeric"> DISC II <br/> {{ \App\Support\Formatter::currency($totalDiskonCdAtauDiskonDua) }} </td> {{-- CD% --}}
                        <td class="numeric">
                            TOTAL Rp.
                            <pr></pr>

                            {{ \App\Support\Formatter::currency($jumlahHargaDenganDiskonDanPajak) }}

                        </td> {{-- Jumlah Harga Rp. --}}
                    </tr>
                </tfoot>
            @endif
        </table>
    </section>
@endforeach

</body>
</html>