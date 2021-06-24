<article>
    <x-feature-title>
        <x-icon-purchase-return/>
        @lang("application.purchase-return")
    </x-feature-title>

    <x-breadcrumb>
        <li class="breadcrumb-item active"
            aria-current="page"
        >
            @lang("application.purchase-return")
        </li>
    </x-breadcrumb>

    <x-control-bar>
        <x-filter-input/>
        <x-filter-date/>
        <x-button-link :href="route('retur-pembelian.create')">
            @lang("application.create")
            <x-icon-create/>
        </x-button-link>
    </x-control-bar>

    <div>
        <x-select-search
                inline
                label="Filter {{ __('application.supplier') }}"
                wire:model="pemasok_id"
                :searchUrl="route('pemasok.search')"
        />
    </div>

    <x-messages></x-messages>

    @if($returPembelians->isNotEmpty())
        <div class="table-responsive">
            <x-table>
                <x-thead>
                    <tr>
                        <th> @lang("application.number_symbol") </th>
                        <th> @lang("application.code") </th>
                        <th> @lang("application.returned_at") </th>
                        <th> @lang("application.purchase_invoice") </th>
                        <th> @lang("application.supplier") </th>
                        <x-th-control> @lang("application.controls") </x-th-control>
                    </tr>
                </x-thead>

                <tbody>
                @foreach ($returPembelians as $returPembelian)
                    <tr>
                        <td> {{ $returPembelians->firstItem() + $loop->index }} </td>
                        <td> {{ $returPembelian->getPrefixedNomor() }} </td>
                        <td> {{ \App\Support\Formatter::dayMonthYear($returPembelian->waktu_pengembalian) }} </td>
                        <td>
                            <a href="{{ route("faktur-pembelian.print", $returPembelian->faktur_pembelian_kode) }}"
                               target="_blank"
                            >
                                {{ $returPembelian->fakturPembelian->kode }}
                            </a>
                        </td>
                        <td>
                            {{ $returPembelian->fakturPembelian->pemasok->nama }}
                        </td>
                        <x-td-control>
                            <x-button-link small :href="route('retur-pembelian.print', $returPembelian)" target="_blank">
                                @lang("application.print")
                                <x-icon-print/>
                            </x-button-link>

                            <x-button-edit :href="route('retur-pembelian.edit', $returPembelian)">
                                @lang("application.edit")
                            </x-button-edit>

                            <x-button-destroy :item="$returPembelian"/>
                        </x-td-control>
                    </tr>
                @endforeach
                </tbody>
            </x-table>
        </div>

        <x-pagination-links-container>
            {{ $returPembelians->links() }}
        </x-pagination-links-container>
    @else
        <x-alert-no-data/>
    @endif
</article>
