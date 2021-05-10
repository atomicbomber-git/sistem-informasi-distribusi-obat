<article>
    <x-feature-title>
        <x-icon-sales-return/>
        @lang("application.sales-return")
    </x-feature-title>

    <x-breadcrumb>
        <li class="breadcrumb-item active"
            aria-current="page"
        >
            @lang("application.sales-return")
        </li>
    </x-breadcrumb>

    <x-control-bar>
        <x-filter-input/>
        <x-button-link :href="route('retur-penjualan.create')">
            @lang("application.create")
            <x-icon-create/>
        </x-button-link>
    </x-control-bar>

    <div>
        <x-select-search
                inline
                label="Filter {{ __('application.customer') }}"
                wire:model="pelanggan_id"
                :searchUrl="route('pelanggan.search')"
        />
    </div>

    <x-messages></x-messages>

    @if($returPenjualans->isNotEmpty())
        <div class="table-responsive">
            <x-table>
                <x-thead>
                    <tr>
                        <th> @lang("application.number_symbol") </th>
                        <th> @lang("application.code") </th>
                        <th> @lang("application.returned_at") </th>
                        <th> @lang("application.sales_invoice") </th>
                        <th> @lang("application.customer") </th>
                        <x-th-control> @lang("application.controls") </x-th-control>
                    </tr>
                </x-thead>

                <tbody>
                @foreach ($returPenjualans as $returPenjualan)
                    <tr>
                        <td> {{ $returPenjualans->firstItem() + $loop->index }} </td>
                        <td> {{ $returPenjualan->getPrefixedNomor() }} </td>
                        <td> {{ \App\Support\Formatter::dayMonthYear($returPenjualan->waktu_pengembalian) }} </td>
                        <td>
                            <a href="{{ route("faktur-penjualan.print", $returPenjualan->faktur_penjualan_id) }}"
                               target="_blank"
                            >
                                {{ $returPenjualan->fakturPenjualan->getPrefixedNomor() }}
                            </a>
                        </td>
                        <td>
                            {{ $returPenjualan->fakturPenjualan->pelanggan->nama }}
                        </td>
                        <x-td-control>
                            <x-button-link small :href="route('retur-penjualan.print', $returPenjualan)" target="_blank">
                                @lang("application.print")
                                <x-icon-print/>
                            </x-button-link>

                            <x-button-edit :href="route('retur-penjualan.edit', $returPenjualan)">
                                @lang("application.edit")
                            </x-button-edit>

                            <x-button-destroy :item="$returPenjualan"/>
                        </x-td-control>
                    </tr>
                @endforeach
                </tbody>
            </x-table>
        </div>

        <x-pagination-links-container>
            {{ $returPenjualans->links() }}
        </x-pagination-links-container>
    @else
        <x-alert-no-data/>
    @endif
</article>
