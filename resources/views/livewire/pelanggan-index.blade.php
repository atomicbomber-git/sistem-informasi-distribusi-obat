<article>
    <x-feature-title>
        <x-icon-product/>
        @lang("application.customer")
    </x-feature-title>

    <x-breadcrumb>
        <li class="breadcrumb-item active"
            aria-current="page"
        >
            @lang("application.customer")
        </li>
    </x-breadcrumb>

    <x-control-bar>
        <x-filter-input/>
        <x-button-link :href="route('pelanggan.create')">
            @lang("application.create")
            <x-icon-create/>
        </x-button-link>
    </x-control-bar>

    <x-messages></x-messages>

    @if($pelanggans->isNotEmpty())
        <div class="table-responsive">
            <x-table>
                <x-thead>
                    <tr>
                        <th> @lang("application.number_symbol") </th>
                        <th> @lang("application.name") </th>
                        <th> @lang("application.address") </th>
                        <x-th-control> @lang("application.controls") </x-th-control>
                    </tr>
                </x-thead>

                <tbody>
                @foreach ($pelanggans as $pelanggan)
                    <tr>
                        <td> {{ $pelanggans->firstItem() + $loop->index }} </td>
                        <td> {{ $pelanggan->nama }} </td>
                        <td> {{ $pelanggan->alamat }} </td>
                        <x-control-td>
                            <x-button-edit :href="route('pelanggan.edit', $pelanggan)">
                                @lang("application.edit")
                            </x-button-edit>

                            <x-button-destroy
                                :item="$pelanggan"
                            />
                        </x-control-td>
                    </tr>
                @endforeach
                </tbody>
            </x-table>
        </div>

        <x-pagination-links-container>
            {{ $pelanggans->links() }}
        </x-pagination-links-container>
    @else
        <x-alert-no-data/>
    @endif`
</article>
