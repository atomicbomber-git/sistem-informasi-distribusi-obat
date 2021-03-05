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
                        <th> @lang("application.quantity_in_hand") </th>
                        <x-th-control> @lang("application.controls") </x-th-control>
                    </tr>
                </x-thead>

                <tbody>
                @foreach ($produks as $produk)
                    <tr>
                        <td> {{ $produks->firstItem() + $loop->index }} </td>
                        <td> {{ $produk->nama }} </td>
                        <td> {{ $produk->kode }} </td>
                        <td> {{ $produk->quantity_in_hand }} </td>
                        <x-td-control>
                            <x-button-link href="">
                                @lang("application.detail")
                                <i class="bi-list-nested"></i>
                            </x-button-link>
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
