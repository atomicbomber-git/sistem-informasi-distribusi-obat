<article>
    <x-feature-title>
        <x-icon-sales-invoice/>
        @lang("application.sales_invoice")
    </x-feature-title>

    <x-breadcrumb>
        <li class="breadcrumb-item active"
            aria-current="page"
        >
            @lang("application.sales_invoice")
        </li>
    </x-breadcrumb>

    <x-control-bar>
        <x-filter-input/>
        <x-button-link :href="route('faktur-penjualan.create')">
            @lang("application.create")
            <x-icon-create/>
        </x-button-link>
    </x-control-bar>

    <x-messages></x-messages>

    @if($fakturPenjualans->isNotEmpty())
        <div class="table-responsive">
            <x-table>
                <x-thead>
                    <tr>
                        <th> @lang("application.number_symbol") </th>
                        <th> @lang("application.code") </th>
                        <th> @lang("application.customer") </th>
                        <th> @lang("application.delivered_at") </th>
                    </tr>
                </x-thead>

                <tbody>
                @foreach ($fakturPenjualans as $fakturPenjualan)
                    <tr>
                        <td> {{ $fakturPenjualans->firstItem() + $loop->index }} </td>
                        <td>  {{ $fakturPenjualan->kode }} </td>
                        <td>  {{ $fakturPenjualan->pelanggan }} </td>
                        <td>  {{ \App\Support\Formatter::dayMonthYear($fakturPenjualan->waktu_pengeluaran) }} </td>
                    </tr>
                @endforeach
                </tbody>
            </x-table>
        </div>

        <x-pagination-links-container>
            {{ $fakturPenjualans->links() }}
        </x-pagination-links-container>
    @else
        <x-alert-no-data/>
    @endif
</article>
