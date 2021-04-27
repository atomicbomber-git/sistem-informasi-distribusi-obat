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

{{--    @if($returPenjualan->isNotEmpty())--}}
{{--        <div class="table-responsive">--}}
{{--            <x-table>--}}
{{--                <x-thead>--}}
{{--                    <tr>--}}
{{--                        <th> @lang("application.number_symbol") </th>--}}
{{--                        <th> @lang("application.code") </th>--}}
{{--                        <th> @lang("application.customer") </th>--}}
{{--                        <th> @lang("application.delivered_at") </th>--}}
{{--                        <x-th-control> @lang("application.controls") </x-th-control>--}}
{{--                    </tr>--}}
{{--                </x-thead>--}}

{{--                <tbody>--}}
{{--                @foreach ($returPenjualan as $fakturPenjualan)--}}
{{--                    <tr>--}}
{{--                        <td> {{ $returPenjualan->firstItem() + $loop->index }} </td>--}}
{{--                        <td> {{ $fakturPenjualan->getNomor() }} </td>--}}
{{--                        <td> {{ $fakturPenjualan->pelanggan->nama }} </td>--}}
{{--                        <td> {{ \App\Support\Formatter::normalDate($fakturPenjualan->waktu_pengeluaran) }} </td>--}}
{{--                        <x-td-control>--}}
{{--                            <x-button-edit :href="route('retur-penjualan.edit', $fakturPenjualan)">--}}
{{--                                @lang("application.edit")--}}
{{--                            </x-button-edit>--}}

{{--                            <x-button-destroy--}}
{{--                                    :item="$fakturPenjualan"--}}
{{--                            />--}}
{{--                        </x-td-control>--}}
{{--                    </tr>--}}
{{--                @endforeach--}}
{{--                </tbody>--}}
{{--            </x-table>--}}
{{--        </div>--}}

{{--        <x-pagination-links-container>--}}
{{--            {{ $returPenjualan->links() }}--}}
{{--        </x-pagination-links-container>--}}
{{--    @else--}}
{{--        <x-alert-no-data/>--}}
{{--    @endif--}}
</article>
