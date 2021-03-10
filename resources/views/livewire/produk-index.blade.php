<article>
    <x-feature-title>
        <i class="bi-box"></i>
        @lang("application.product")
    </x-feature-title>

    <x-breadcrumb>
        <li class="breadcrumb-item active"
            aria-current="page"
        >
            @lang("application.product")
        </li>
    </x-breadcrumb>

    <x-control-bar>
        <x-filter-input/>
        <x-button-link :href="route('produk.create')">
            @lang("application.create")
            <i class="bi-plus-circle"></i>
        </x-button-link>
    </x-control-bar>

    <x-messages></x-messages>

    @if($produks->isNotEmpty())
        <div class="table-responsive">
            <x-table>
                <x-thead>
                    <tr>
                        <th> @lang("application.number_symbol") </th>
                        <th> @lang("application.name") </th>
                        <th> @lang("application.code") </th>
                        <th class="text-end"> @lang("application.quantity_in_hand") </th>
                        <x-th-control> @lang("application.controls") </x-th-control>
                    </tr>
                </x-thead>

                <tbody>
                @foreach ($produks as $produk)
                    <tr wire:key="{{ $produk->getKey() }}">
                        <td> {{ $produks->firstItem() + $loop->index }} </td>
                        <td> {{ $produk->nama }} </td>
                        <td> {{ $produk->kode }} </td>
                        <td class="text-end">
                            {{ \App\Support\Formatter::quantity($produk->quantity_in_hand) }}
                        </td>
                        <x-td-control>
                            <x-button-detail :href="route('produk.stock.index', $produk)">
                                @lang("application.stock")
                            </x-button-detail>

                            <x-button-edit :href="route('produk.edit', $produk)">
                                @lang("application.edit")
                            </x-button-edit>
                            <x-button-destroy :item="$produk" />
                        </x-td-control>
                    </tr>
                @endforeach
                </tbody>
            </x-table>
        </div>

        <x-pagination-links-container>
            {{ $produks->links() }}
        </x-pagination-links-container>
    @else
        <x-alert-no-data/>
    @endif
</article>
