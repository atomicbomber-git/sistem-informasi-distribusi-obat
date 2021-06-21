<article>
    <x-feature-title>
        <x-icon-audit/>
        @lang("application.audit")
    </x-feature-title>

    <x-breadcrumb>
        <li class="breadcrumb-item active"
            aria-current="page"
        >
            @lang("application.audit")
        </li>
    </x-breadcrumb>

    <x-messages></x-messages>

    @if($audits->isNotEmpty())
        <div class="table-responsive">
            <x-table>
                <x-thead>
                    <tr>
                        <th> @lang("application.name") </th>
                        <th> Tipe Data </th>
                        <th> Data Sebelum Revisi </th>
                        <th> Data Setelah Revisi </th>
                        <th> Waktu Revisi </th>
                    </tr>
                </x-thead>

                <tbody>
                @foreach ($audits as $audit)
                    <tr wire:key="{{ $audit->getKey() }}">
                        <td> {{ $audit->user->name  }} </td>
                        <td> {{ $this->getAuditableClassNames()[$audit->auditable_type] }} </td>
                        <td>
                            @if(empty($audit->old_values))
                                <span class="badge bg-success">
                                    Data Baru
                                </span>
                            @else
                                @switch($audit->auditable_type)
                                    @case(\App\Models\ItemFakturPembelian::class)
                                        <x-dl-item-faktur-pembelian
                                            :itemFakturPembelian="$audit->old_values"
                                        />
                                    @break
                                    @case(\App\Models\FakturPembelian::class)
                                    <x-dl-faktur-pembelian
                                            :fakturPembelian="$audit->old_values"
                                    />
                                    @break
                                @endswitch
                            @endif
                        </td>
                        <td>
                            @if(empty($audit->new_values))
                                <span class="badge bg-danger">
                                    Data Dihapus
                                </span>
                            @else
                                @switch($audit->auditable_type)
                                    @case(\App\Models\ItemFakturPembelian::class)
                                    <x-dl-item-faktur-pembelian
                                            :itemFakturPembelian="$audit->new_values"
                                    />
                                    @break
                                    @case(\App\Models\FakturPembelian::class)
                                    <x-dl-faktur-pembelian
                                            :fakturPembelian="$audit->new_values"
                                    />
                                    @break
                                @endswitch
                            @endif
                        </td>
                        <td> {{ $audit->created_at  }} </td>
                    </tr>
                @endforeach
                </tbody>
            </x-table>
        </div>

        <x-pagination-links-container>
            {{ $audits->links() }}
        </x-pagination-links-container>
    @else
        <x-alert-no-data/>
    @endif
</article>
