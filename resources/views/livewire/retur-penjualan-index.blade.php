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

    <x-messages></x-messages>

    @if($returPenjualans->isNotEmpty())
        <div class="table-responsive">
            <x-table>
                <x-thead>
                    <tr>
                        <th> @lang("application.number_symbol") </th>
                        <th> @lang("application.returned_at") </th>
                        <x-th-control> @lang("application.controls") </x-th-control>
                    </tr>
                </x-thead>

                <tbody>
                @foreach ($returPenjualans as $returPenjualan)
                    <tr>
                        <td> {{ $returPenjualans->firstItem() + $loop->index }} </td>
                        <td> {{ \App\Support\Formatter::dayMonthYear($returPenjualan->waktu_pengembalian) }} </td>
                        <x-td-control>
                            {{-- TODO: Implement delete --}}
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
