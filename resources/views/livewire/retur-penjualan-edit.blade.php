<article>
    <x-feature-title>
        <x-icon-edit/>
        @lang("application.edit")
    </x-feature-title>

    <x-breadcrumb>
        <li class="breadcrumb-item">
            <a href="{{ route("retur-penjualan.index") }}">
                @lang("application.sales-return")
            </a>
        </li>

        <li class="breadcrumb-item active"
            aria-current="page"
        >
            @lang("application.edit")
        </li>
    </x-breadcrumb>

    <x-messages/>

    <form class="card" wire:submit.prevent="submit">
        <div class="card-body">
            <x-input
                    livewire group
                    field="returPenjualan.nomor"
                    :label="__('application.code')"
            >
                <x-slot name="input_prefix">
                    <span class="input-group-text"> {{ $returPenjualan->getNomorPrefix() }}  </span>
                </x-slot>
            </x-input>

            <x-input
                    livewire
                    field="returPenjualan.waktu_pengembalian"
                    :label="__('application.returned_at')"
                    type="datetime-local"
            />

            <x-table>
                <x-thead>
                    <tr>
                        <th> @lang("application.number_symbol") </th>
                        <th> @lang("application.product") </th>
                        <th> @lang("application.batch_code") </th>
                        <th class="text-end"> @lang("application.sales-quantity") </th>
                        <th class="text-end"> @lang("application.quantity") </th>
                        <th> @lang("application.reason") </th>
                        <th class="text-center"> @lang("application.controls") </th>
                    </tr>
                </x-thead>

                <tbody>
                @foreach ($itemReturPenjualans as $key => $itemReturPenjualan)
                    <tr wire:key="{{ $key }}">
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $itemReturPenjualan->mutasiStockPenjualan->stock->produk->nama }} </td>
                        <td> {{ $itemReturPenjualan->mutasiStockPenjualan->stock->kode_batch }} </td>
                        <td class="text-end"> {{ -$itemReturPenjualan->mutasiStockPenjualan->jumlah }} </td>
                        <td>
                            <x-input-livewire-numeric
                                    wire:key="input-{{ $key }}"
                                    inline small
                                    :label='__("application.quantity") . " " . $itemReturPenjualan->mutasiStockPenjualan->stock->produk->nama ?? null . " batch " . $itemReturPenjualan->mutasiStockPenjualan->stock->kode_batch ?? null'
                                    wire:model.defer='itemReturPenjualans.{{ $key }}.jumlah'
                            />
                        </td>
                        <td>
                            <label for="alasan_{{ $key }}" class="visually-hidden">
                                {{ __("application.alasan") . " " . ($itemReturPenjualan->mutasiStockPenjualan->stock->produk->nama ?? null) . " batch " . ($itemReturPenjualan->mutasiStockPenjualan->stock->kode_batch ?? null) }}
                            </label>
                            <select
                                    class="form-select form-select-sm"
                                    wire:model="itemReturPenjualans.{{ $key }}.alasan"
                                    name="alasan_{{ $key }}"
                                    id="alasan_{{ $key }}"
                            >
                                @foreach (\App\Models\ItemReturPenjualan::REASONS as $reason)
                                    <option value="{{ $reason }}">
                                        {{ __("application.{$reason}")  }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="text-center">
                            <button
                                    wire:click="removeItem({{ $key }})"
                                    class="btn btn-danger btn-sm" type="button">
                                <x-icon-destroy/>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </x-table>


            <div wire:ignore>
                <label class="form-label"
                       for="item-add"
                >
                    @lang("application.add_item")
                </label>
                <select
                        id="item-add"
                        wire:ignore
                        style="width: 100%"
                        x-data
                        x-init="
                        (function() {
                            $($el).select2({
                                ajax: { url: '{{ route('faktur-penjualan.search-item', $returPenjualan->faktur_penjualan_id) }}' },
                                theme: 'bootstrap-5'
                            }).change(e => {
                                if (!!e.target.value) {
                                    $wire.call('addItem', e.target.value)
                                    $(e.target).val(null).trigger('change')
                                }
                            })
                        })()"
                ></select>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
            <button class="btn btn-primary">
                @lang("application.update")
                <x-icon-edit/>
            </button>
        </div>
    </form>
</article>
