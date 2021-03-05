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

        <x-button-link :href="route('faktur-pembelian.create')">
            @lang("application.create")
            <i class="bi-plus-circle"></i>
        </x-button-link>
    </x-control-bar>

    <x-messages></x-messages>

    @if($faktur_pembelians->isNotEmpty())
        <div class="table-responsive">
            <x-table>
                <x-thead>
                    <tr>
                        <th> @lang("application.number_symbol") </th>
                        <th> @lang("application.code") </th>
                        <th> @lang("application.supplier") </th>
                        <th class="text-end"> @lang("application.discount_percentage") </th>
                        <th> @lang("application.sold_at") </th>
                        <x-th-control> @lang("application.controls") </x-th-control>
                    </tr>
                </x-thead>

                <tbody>
                @foreach ($faktur_pembelians as $faktur_pembelian)
                    <tr>
                        <td> {{ $faktur_pembelians->firstItem() + $loop->index }} </td>
                        <td> {{ $faktur_pembelian->kode }} </td>
                        <td> {{ $faktur_pembelian->pemasok }} </td>
                        <td class="text-end"> {{ \App\Support\Formatter::percentage($faktur_pembelian->persentase_diskon) }} </td>
                        <td> {{ $faktur_pembelian->waktu_penjualan }} </td>
                        <x-th-control>
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
