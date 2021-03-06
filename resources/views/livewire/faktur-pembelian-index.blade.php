<article>
    <x-feature-title>
        <i class="bi-card-list"></i>
        @lang("application.purchase_invoice")
    </x-feature-title>

    <x-breadcrumb>
        <li class="breadcrumb-item active"
            aria-current="page"
        >
            @lang("application.purchase_invoice")
        </li>
    </x-breadcrumb>

    <x-control-bar>
        <x-filter-input/>
        <x-filter-date/>

        <x-button-link :href="route('faktur-pembelian.create')">
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

    @if($faktur_pembelians->isNotEmpty())
        <div class="table-responsive">
            <x-table>
                <x-thead>
                    <tr>
                        <th> @lang("application.number_symbol") </th>
                        <th> @lang("application.code") </th>
                        <th> @lang("application.supplier") </th>
                        <th> @lang("application.arrived_at") </th>
                        <x-th-control> @lang("application.controls") </x-th-control>
                    </tr>
                </x-thead>

                <tbody>
                @foreach ($faktur_pembelians as $faktur_pembelian)
                    <tr wire:key="{{ $faktur_pembelian->getKey() }}">
                        <td> {{ $faktur_pembelians->firstItem() + $loop->index }} </td>
                        <td> {{ $faktur_pembelian->kode }} </td>
                        <td> {{ $faktur_pembelian->pemasok->nama }} </td>
                        <td> {{ \App\Support\Formatter::normalDate($faktur_pembelian->waktu_penerimaan) }} </td>
                        <x-th-control>
                            <x-button-link
                                    small
                                    :href="route('faktur-pembelian.print', $faktur_pembelian)"
                                    target="_blank"
                            >
                                @lang("application.print")
                                <x-icon-print/>
                            </x-button-link>

                            <x-button-edit :href="route('faktur-pembelian.edit', $faktur_pembelian)">
                                @lang("application.edit")
                            </x-button-edit>

                            <x-button-destroy
                                    :item="$faktur_pembelian"
                            />
                        </x-th-control>
                    </tr>
                @endforeach
                </tbody>
            </x-table>
        </div>

        <x-pagination-links-container>
            {{ $faktur_pembelians->links() }}
        </x-pagination-links-container>
    @else
        <x-alert-no-data/>
    @endif
</article>
