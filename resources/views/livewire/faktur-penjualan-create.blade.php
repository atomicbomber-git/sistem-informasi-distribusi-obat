<article>
    <x-feature-title>
        <i class="bi-plus-circle"></i>
        @lang("application.create")
    </x-feature-title>

    <x-table>
        <x-thead>
            <th> @lang("application.number_symbol") </th>
            <th> @lang("application.product") </th>
            <th class="text-end"> @lang("application.quantity") </th>
            <th class="text-end"> @lang("application.unit_price") </th>
            <th class="text-end"> @lang("application.discount_percentage") </th>
            <th class="text-end"> @lang("application.subtotal") </th>
            <x-th-control> @lang("application.controls") </x-th-control>
        </x-thead>

        <tbody>
        @foreach ($item_faktur_penjualans as $key => $item_faktur_penjualan)
            <tr wire:key="{{ $key }}">
                <td> {{ $loop->iteration }} </td>
                <td> {{ $item_faktur_penjualan["produk"]["nama"] }} </td>
                <td class="text-end">
                    <x-input-inline-numeric-lv
                            :label='__("application.quantity") . " " . $item_faktur_penjualan["produk"]["nama"]'
                            :key='"jumlah_{$loop->index}"'
                            :field='"item_faktur_penjualans.{$key}.jumlah"'
                    />
                </td>
                <td class="text-end">
                    <x-input-inline-numeric-lv
                            :label='__("application.unit_price") . " " . $item_faktur_penjualan["produk"]["nama"]'
                            :key='"harga_satuan_{$loop->index}"'
                            :field='"item_faktur_penjualans.{$key}.harga_satuan"'
                    />
                </td>
                <td class="text-end">
                    <x-input-inline-numeric-lv
                            :label='__("application.discount_percentage") . " " . $item_faktur_penjualan["produk"]["nama"]'
                            :key='"persentase_diskon_{$loop->index}"'
                            :field='"item_faktur_penjualans.{$key}.persentase_diskon"'
                    />
                </td>
                <td class="text-end">
                    {{ \App\Support\Formatter::currency($item_faktur_penjualan["subtotal"]) }}
                </td>
                <x-td-control>
                    <button
                            x-data="{}"
                            x-on:click="@this.call('removeItem', '{{ $key }}')  "
                            type="button"
                            class="btn btn-sm btn-danger"
                    >
                        <i class="bi-trash"></i>
                    </button>
                </x-td-control>
            </tr>
        @endforeach
        </tbody>


        <tfoot>
            <tr>
                <td colspan="4"></td>
                <td class="text-end fw-bold">
                    @lang("application.total_before_bulk_discount")
                </td>
                <td class="text-end">
                    {{ \App\Support\Formatter::currency($total_before_bulk_discount) }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td class="text-end fw-bold">
                    @lang("application.bulk_discount")
                </td>
                <td class="text-end">
                    <x-input-inline-numeric-lv
                            :label='__("application.bulk_discount")'
                            key="persentase_diskon_grosir"
                            field="persentase_diskon_grosir"
                    />
                </td>
                <td></td>
            </tr>
        </tfoot>
    </x-table>

    <div
            wire:ignore
            class="d-flex justify-content-end form-inline py-3"
    >
        <label for="barang_id"
               class="me-3 form-label"
        >
            @lang("application.add_item")
        </label>

        <select
                class="flex-grow-1"
                id="barang_id"
                name="barang_id"
        >
        </select>

        @push("scripts")
            <script type="application/javascript">
                $("#barang_id").select2({
                    ajax: {url: "{{ route("produk.search") }}",},
                    theme: 'bootstrap-5',
                }).change(e => {
                    @this.call('addItem', e.target.value)
                })
            </script>
        @endpush
    </div>
</article>
