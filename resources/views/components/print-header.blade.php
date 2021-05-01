@props([
    "fakturPenjualan" => new \App\Models\FakturPenjualan(),
])

<header>
    <div style="display: flex">
        <div style="flex: 1; border: thin solid black; padding: 1rem">
            <div style="text-align: center"> PT KUBURAYA MEDIFARMA </div>
            <table>
                <tbody>
                <tr> <td style="white-space: nowrap; text-align: left" > Izin PBF </td> <td> : </td> <td> HK.07.01/V/387/14 </td> </tr>
                <tr> <td style="white-space: nowrap; text-align: left" > Izin PAK </td> <td> : </td> <td> HK.07.ALKES/IV/428/AK-2/2011 </td> </tr>
                <tr> <td style="white-space: nowrap; text-align: left" > Alamat </td> <td> : </td> <td> Jl. Sei Raya Dalam Kom. Ruko Taman Sei Raya No. R3 Kab. Kubu Raya Telp. 0561-710377 / Fax. 0561-711355 </td> </tr>
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
        <div> No Faktur: {{ $fakturPenjualan->getNomorPrefix() }} </div>
        <div> Tgl. Faktur: {{ \App\Support\Formatter::dayMonthYear($fakturPenjualan->waktu_pengeluaran) }} </div>
    </div>
</header>