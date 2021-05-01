<article>
    <x-feature-title>
        <x-icon-create/>
        @lang("application.create")
    </x-feature-title>

    <x-breadcrumb>
        <li class="breadcrumb-item">
            <a href="{{ route("faktur-pembelian.index") }}">
                @lang("application.purchase_invoice")
            </a>
        </li>

        <li class="breadcrumb-item active"
            aria-current="page"
        >
            @lang("application.create")
        </li>
    </x-breadcrumb>

    <form wire:submit.prevent="submit"
          class="card"
    >
        <div class="card-body">
            <x-input
                    livewire
                    field="kode"
                    :label="__('application.code')"
            />

            <x-select-search
                    :searchUrl="route('pemasok.search')"
                    :label="__('application.supplier')"
                    wire:model="pemasok_id"
            />

            <x-input
                    livewire
                    field="waktu_penerimaan"
                    :label="__('application.arrived_at')"
                    type="datetime-local"
            />

            <h2 class="h4">
                @lang("application.item_list")
            </h2>

            @error("item_faktur_pembelians")
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror

            <x-table>
                <x-thead>
                    <th> @lang("application.number_symbol") </th>
                    <th> @lang("application.product") (@lang("application.unit")) </th>
                    <th> @lang("application.batch_code") </th>
                    <th> @lang("application.expired_at") </th>
                    <th class="text-end"> @lang("application.quantity") </th>
                    <th class="text-end"> @lang("application.unit_price") </th>
                    <th class="text-end"> @lang("application.subtotal") </th>
                    <x-th-control> @lang("application.controls") </x-th-control>
                </x-thead>

                <tbody>
                @foreach ($itemFakturPembelians as $key => $item_faktur_pembelian)
                    <tr wire:key="{{ $key }}">
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $item_faktur_pembelian["produk"]["nama"] }} ({{ $item_faktur_pembelian["produk"]["satuan"] }}) </td>
                        <td>
                            <x-input
                                    inline small livewire
                                    :label='__("application.batch_code") . " " . $item_faktur_pembelian["produk"]["nama"]'
                                    :field='"item_faktur_pembelians.{$key}.kode_batch"'
                            />
                        </td>
                        <td>
                            <x-input
                                    inline small livewire
                                    type="date"
                                    :label='__("application.expired_at") . " " . $item_faktur_pembelian["expired_at"]'
                                    :field='"item_faktur_pembelians.{$key}.expired_at"'
                            />
                        </td>
                        <td class="text-end">
                            <x-lv-input-numeric
                                    inline small
                                    :label='__("application.quantity") . " " . $item_faktur_pembelian["produk"]["nama"]'
                                    :key='"jumlah_{$loop->index}"'
                                    :field='"item_faktur_pembelians.{$key}.jumlah"'
                            />
                        </td>
                        <td class="text-end">
                            <x-lv-input-numeric
                                    inline small
                                    :label='__("application.unit_price") . " " . $item_faktur_pembelian["produk"]["nama"]'
                                    :key='"harga_satuan_{$loop->index}"'
                                    :field='"item_faktur_pembelians.{$key}.harga_satuan"'
                            />
                        </td>
                        <td class="text-end">
                            {{ \App\Support\Formatter::currency($item_faktur_pembelian["subtotal"]) }}
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
                    <td colspan="6" class="text-end fw-bold">
                        @lang("application.total")
                    </td>
                    <td class="text-end">
                        {{ \App\Support\Formatter::currency($total) }}
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
                        }).change(function (e) {
                            if (e.target.value) {
                            @this.call("addItem", e.target.value)
                                $(this).val(null).trigger("change")
                            }
                        })
                    </script>
                @endpush
            </div>
        </div>

        <x-card-footer-submit>
            <x-submit-button>
                @lang("application.create")
                <x-icon-create/>
            </x-submit-button>
        </x-card-footer-submit>
    </form>
</article>
