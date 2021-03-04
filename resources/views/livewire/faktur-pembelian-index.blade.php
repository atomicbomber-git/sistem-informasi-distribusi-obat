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

    @if($faktur_penjualans->isNotEmpty())
        <div class="table-responsive">
            <x-table>
                <x-thead>
                    <tr>
                        <th> @lang("application.number_symbol") </th>
                        <th> @lang("application.code") </th>
                        <th> @lang("application.customer") </th>
                        <th class="text-end"> @lang("application.discount_percentage") </th>
                        <th> @lang("application.sold_at") </th>
                        <x-th-control> @lang("application.controls") </x-th-control>
                    </tr>
                </x-thead>

                <tbody>
                @foreach ($faktur_penjualans as $faktur_penjualan)
                    <tr>
                        <td> {{ $faktur_penjualans->firstItem() + $loop->index }} </td>
                        <td> {{ $faktur_penjualan->kode }} </td>
                        <td> {{ $faktur_penjualan->pelanggan }} </td>
                        <td class="text-end"> {{ \App\Support\Formatter::percentage($faktur_penjualan->persentase_diskon) }} </td>
                        <td> {{ $faktur_penjualan->waktu_penjualan }} </td>
                        <x-th-control>
                            <x-button-destroy
                                :item="$faktur_penjualan"
                            />
                        </x-th-control>
                    </tr>
                @endforeach
                </tbody>
            </x-table>
        </div>

        <x-pagination-links-container>
            {{ $faktur_penjualans->links() }}
        </x-pagination-links-container>
    @else
        <x-alert-no-data/>
    @endif
</article>
