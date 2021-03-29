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

    <div>
        <x-select-search
                inline
                label="Filter {{ __('application.customer') }}"
                wire:model="pelangganId"
                :searchUrl="route('pelanggan.search')"
        />
    </div>


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
                        <x-th-control> @lang("application.controls") </x-th-control>
                    </tr>
                </x-thead>

                <tbody>
                @foreach ($fakturPenjualans as $fakturPenjualan)
                    <tr>
                        <td> {{ $fakturPenjualans->firstItem() + $loop->index }} </td>
                        <td> {{ $fakturPenjualan->getNomor() }} </td>
                        <td> {{ $fakturPenjualan->pelanggan->nama }} </td>
                        <td> {{ \App\Support\Formatter::normalDate($fakturPenjualan->waktu_pengeluaran) }} </td>
                        <x-td-control>
                            <x-button-edit :href="route('faktur-penjualan.edit', $fakturPenjualan)">
                                @lang("application.edit")
                            </x-button-edit>

                            <x-button-destroy
                                    :item="$fakturPenjualan"
                            />
                        </x-td-control>
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
