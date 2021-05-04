<article>
    <x-feature-title>
        <i class="bi-box"></i>
        @lang("application.stock")
    </x-feature-title>

    <x-breadcrumb>
        <li class="breadcrumb-item">
            <a href="{{ route('produk.index') }}">
                @lang("application.product")
            </a>
        </li>

        <li class="breadcrumb-item">
            {{ $produk->nama }} ({{ $produk->kode }})
        </li>
    </x-breadcrumb>

    <x-control-bar>
        <x-filter-input/>
    </x-control-bar>

    <x-messages></x-messages>

    @if($stocks->isNotEmpty())
        <div class="table-responsive">
            <x-table>
                <x-thead>
                    <tr>
                        <th> @lang("application.number_symbol") </th>
                        <th> @lang("application.batch_code") </th>
                        <x-numeric-th> @lang("application.quantity_in_hand") </x-numeric-th>
                        <x-numeric-th> @lang("application.unit_value") </x-numeric-th>
                        <th class="text-center"> @lang("application.expired_at") </th>
                        <th> @lang("application.status") </th>
                        <x-th-control> @lang("application.controls") </x-th-control>
                    </tr>
                </x-thead>

                <tbody>
                @foreach ($stocks as $stock)
                    <tr wire:key="{{ $stock->getKey() }}">
                        <td> {{ $stocks->firstItem() + $loop->index }} </td>
                        <td> {{ $stock->kode_batch }} </td>
                        <x-numeric-td> {{ \App\Support\Formatter::quantity($stock->jumlah) }} </x-numeric-td>
                        <x-numeric-td> {{ \App\Support\Formatter::currency($stock->nilai_satuan) }} </x-numeric-td>
                        <td class="text-center">
                            {{ \App\Support\Formatter::normalDate($stock->expired_at) }}
                            <br/>
                            <span class="fw-bold text-primary">
                                ({{ \App\Support\Formatter::humanDiff($stock->expired_at) }})
                            </span>
                        </td>
                        <td class="text-uppercase">
                            {{ $stock->status }}
                        </td>
                        <x-td-control>
                            @if($stock->original_mutation->tipe === \App\Enums\TipeMutasiStock::PEMBELIAN)
                                <x-button-link
                                    :href="route('faktur-pembelian.edit', $stock->original_mutation->item_faktur_pembelian->faktur_pembelian_kode)"
                                >
                                    @lang("application.purchase_invoice")
                                    <x-icon-purchase-invoice/>
                                </x-button-link>
                            @endif

                            @if($stock->original_mutation->item_retur_penjualan_id !== null)
                                    <x-button-link :href="route('retur-penjualan.edit', $stock->original_mutation->itemReturPenjualan->returPenjualan)"
                                    >
                                        @lang("application.sales-return")
                                        <x-icon-sales-return/>
                                    </x-button-link>
                            @endif
                        </x-td-control>
                    </tr>
                @endforeach
                </tbody>
            </x-table>
        </div>
        <x-pagination-links-container>
            {{ $stocks->links() }}
        </x-pagination-links-container>
    @else
        <x-alert-no-data/>
    @endif
</article>
