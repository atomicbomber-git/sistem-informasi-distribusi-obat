<article>
    <x-feature-title>
        <x-icon-edit/>
        @lang("application.edit")
    </x-feature-title>

    <x-breadcrumb>
        <li class="breadcrumb-item">
            <a href="{{ route("faktur-penjualan.index") }}">
                @lang("application.sales_invoice")
            </a>
        </li>

        <li class="breadcrumb-item active"
            aria-current="page"
        >
            @lang("application.edit")
        </li>
    </x-breadcrumb>

    <x-messages/>

    <form wire:submit.prevent="submit" class="card">
        <div class="card-body">
            {{-- TODO: Add input-group with KM indicator to this --}}
            <x-input
                    livewire
                    field="fakturPenjualan.nomor"
                    :label="__('application.code')"
            />

            <x-input
                    livewire
                    field="fakturPenjualan.pelanggan"
                    :label="__('application.customer')"
            />

            <x-input
                    livewire
                    field="fakturPenjualan.waktu_pengeluaran"
                    :label="__('application.delivered_at')"
                    type="datetime-local"
            />

            <h2 class="h4">
                @lang("application.item_list")
            </h2>

            @error("itemFakturPenjualans")
            <div class="alert alert-danger">
                {{ $message }}
            </div>
            @enderror

            <x-table>
                <x-thead>
                    <th> @lang("application.number_symbol") </th>
                    <th> @lang("application.product") </th>
                    <th class="text-end"> @lang("application.quantity_in_hand") </th>
                    <th class="text-end"> @lang("application.quantity") </th>
                    <th class="text-end"> @lang("application.unit_price") </th>
                    <th class="text-end"> @lang("application.discount_percentage") </th>
                    <th class="text-end"> @lang("application.subtotal") </th>
                    <x-th-control> @lang("application.controls") </x-th-control>
                </x-thead>

                <tbody>
                @foreach ($itemFakturPenjualans as $key => $itemFakturPenjualan)
                    <tr class="{{  !array_key_exists($key, $removedOriginalItemKeys) ?: 'table-danger' }}">
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $itemFakturPenjualan->produk->nama }} </td>
                        <td class="text-end">
                            {{ $itemFakturPenjualan->produk->quantity_in_hand }}
                        </td>
                        <td class="text-end">
                            <x-input-livewire-numeric
                                    inline
                                    :label='__("application.quantity") . " {$itemFakturPenjualan->produk->nama}"'
                                    wire:model.lazy='itemFakturPenjualans.{{ $key }}.jumlah'
                            />
                        </td>

                        <td class="text-end">
                            <x-input-livewire-numeric
                                    inline
                                    :label='__("application.unit_price") . " {$itemFakturPenjualan->produk->nama}"'
                                    wire:model.lazy='itemFakturPenjualans.{{ $key }}.harga_satuan'
                            />
                        </td>

                        <td class="text-end">
                            <x-input-livewire-numeric
                                    inline
                                    :label='__("application.discount") . " {$itemFakturPenjualan->produk->nama}"'
                                    wire:model.lazy='itemFakturPenjualans.{{ $key }}.diskon'
                            />
                        </td>
                        <td class="text-end">
                            {{ \App\Support\Formatter::quantity($itemFakturPenjualan->getSubtotal()) }}
                        </td>

                        <x-td-control>
                            @if(! array_key_exists($key, $removedOriginalItemKeys))
                                <button
                                        wire:click="removeItem('{{ $key }}')"
                                        type="button"
                                        class="btn btn-sm btn-danger">
                                    <x-icon-destroy/>
                                </button>
                            @else
                                <button
                                        wire:click="restoreItem('{{ $key }}')"
                                        type="button"
                                        class="btn btn-sm btn-success">
                                    <x-icon-restore/>
                                </button>
                            @endif
                        </x-td-control>
                    </tr>
                @endforeach
                </tbody>

                <tfoot>
                <tr>
                    <td colspan="6" class="text-end fw-bold">
                        @lang("application.discount_percentage")
                    </td>
                    <td class="text-end">
                        <x-lv-input-numeric
                                inline small
                                :label='__("application.discount_percentage")'
                                field='fakturPenjualan.diskon'
                        />
                    </td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="6" class="text-end fw-bold">
                        @lang("application.tax_percentage")
                    </td>
                    <td class="text-end">
                        <x-lv-input-numeric
                                disabled inline small
                                :label='__("application.discount_percentage")'
                                field='fakturPenjualan.pajak'
                        />
                    </td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="6" class="text-end fw-bold">
                        @lang("application.total")
                    </td>
                    <td class="text-end">
                        {{ \App\Support\Formatter::quantity($this->total()) }}
                    </td>
                    <td></td>
                </tr>
                </tfoot>
            </x-table>

            <div wire:ignore>
                <label class="form-label" for="item-add">
                    @lang("application.add_item")
                </label>
                <select
                        wire:ignore
                        style="width: 100%"
                        x-data
                        x-init="
                    $($el).select2({
                        ajax: { url: '{{ route('produk-in-hand.search') }}' },
                        theme: 'bootstrap-5'
                    }).change(e => {
                        if (!!e.target.value) {
                            $wire.call('addItem', e.target.value)
                            $(e.target).val(null).trigger('change')
                        }
                    })
"
                        id="item-add"
                ></select>
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
