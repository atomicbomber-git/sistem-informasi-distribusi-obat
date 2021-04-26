<article>
    <x-feature-title>
        <x-icon-supplier/>
        @lang("application.supplier")
    </x-feature-title>

    <x-breadcrumb>
        <li class="breadcrumb-item active"
            aria-current="page"
        >
            @lang("application.supplier")
        </li>
    </x-breadcrumb>

    <x-control-bar>
        <x-filter-input/>
        <x-button-link :href="route('pemasok.create')">
            @lang("application.create")
            <x-icon-create/>
        </x-button-link>
    </x-control-bar>

    <x-messages></x-messages>

    @if($pemasoks->isNotEmpty())
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
                @foreach ($pemasoks as $pemasok)
                    <tr>
                        <td> {{ $pemasoks->firstItem() + $loop->index }} </td>
                        <td> {{ $pemasok->nama }} </td>
                        <td> {{ $pemasok->alamat }} </td>
                        <x-control-td>
                            <x-button-edit :href="route('pemasok.edit', $pemasok)">
                                @lang("application.edit")
                            </x-button-edit>

                            <x-button-destroy
                                    :item="$pemasok"
                            />
                        </x-control-td>
                    </tr>
                @endforeach
                </tbody>
            </x-table>
        </div>

        <x-pagination-links-container>
            {{ $pemasoks->links() }}
        </x-pagination-links-container>
    @else
        <x-alert-no-data/>
    @endif`
</article>
