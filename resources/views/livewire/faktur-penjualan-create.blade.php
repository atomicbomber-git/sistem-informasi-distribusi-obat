<article>
    <x-feature-title>
        <x-icon-create/>
        @lang("application.create")
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
            @lang("application.create")
        </li>
    </x-breadcrumb>

    <form wire:submit.prevent="submit" class="card">
        <div class="card-body">

            <x-input
                    livewire
                    field="kode"
                    :label="__('application.code')"
            />

            <x-input
                    livewire
                    field="pelanggan"
                    :label="__('application.customer')"
            />

            <x-input
                    livewire
                    field="waktu_pengeluaran"
                    :label="__('application.delivered_at')"
                    type="datetime-local"
            />

            <h2 class="h4">
                @lang("application.item_list")
            </h2>

            <x-table>
                <x-thead>
                    <th> @lang("application.number_symbol") </th>
                    <th> @lang("application.product") </th>
                    <th class="text-end"> @lang("application.quantity_in_hand") </th>
                    <th class="text-end"> @lang("application.quantity") </th>
                    <th class="text-end"> @lang("application.unit_price") </th>
                    <th class="text-end"> @lang("application.subtotal") </th>
                    <x-th-control> @lang("application.controls") </x-th-control>
                </x-thead>

                <tbody>
                @foreach ($itemFakturPenjualans as $key => $itemFakturPenjualan)
                    <tr wire:key="{{ $key }}">
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $itemFakturPenjualan["produk"]["nama"] }} </td>
                        <td class="text-end">
                            {{ \App\Support\Formatter::quantity($itemFakturPenjualan["produk"]["quantity_in_hand"])  }}
                        </td>
                        <td class="text-end">
                            <x-lv-input-numeric
                                    inline small
                                    :label='__("application.quantity") . " " . $itemFakturPenjualan["produk"]["nama"]'
                                    :key='"jumlah_{$loop->index}"'
                                    :field='"itemFakturPenjualans.{$key}.jumlah"'
                            />
                        </td>
                        <td class="text-end">
                            <x-lv-input-numeric
                                    inline small
                                    :label='__("application.unit_price") . " " . $itemFakturPenjualan["produk"]["nama"]'
                                    :key='"harga_satuan_{$loop->index}"'
                                    :field='"itemFakturPenjualans.{$key}.harga_satuan"'
                            />
                        </td>
                        <td class="text-end">
                            {{ \App\Support\Formatter::currency(\App\Http\Livewire\FakturPenjualanCreate::subTotal($itemFakturPenjualan)) }}
                        </td>
                        <td class="text-center">
                            <button
                                    wire:click="removeItem('{{ $key }}')"
                                    type="button" class="btn btn-sm btn-danger">
                                <x-icon-destroy/>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>

                <tfoot>
                <tr>
                    <td colspan="5" class="text-end fw-bold">
                        @lang("application.total")
                    </td>
                    <td class="text-end">
                        {{ \App\Support\Formatter::currency(\App\Http\Livewire\FakturPenjualanCreate::total($itemFakturPenjualans)) }}
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