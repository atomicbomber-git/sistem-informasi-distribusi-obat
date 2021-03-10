<article>
    <x-feature-title>
        <i class="bi-box"></i>
        @lang("application.product")
    </x-feature-title>

    <x-breadcrumb>
        <li class="breadcrumb-item active"
            aria-current="page">
            @lang("application.product")
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
                        <th> @lang("application.expired_at") </th>
                    </tr>
                </x-thead>

                <tbody>
                @foreach ($stocks as $stock)
                    <tr wire:key="{{ $stock->getKey() }}">
                        <td> {{ $stocks->firstItem() + $stock->index }} </td>
                        <td> {{ $stock->kode_batch }} </td>
                        <x-numeric-td> {{ $stock->jumlah }} </x-numeric-td>
                        <x-numeric-td> {{ \App\Support\Formatter::currency($stock->nilai_satuan) }} </x-numeric-td>
                        <td>
                            {{ \App\Support\Formatter::dayMonthYear($stock->expired_at) }}
                            <span class="fw-bold text-primary">
                                ({{ \App\Support\Formatter::humanDiff($stock->expired_at) }})
                            </span>
                        </td>
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
