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

    <div class="card">
        <div class="card-body">

            <form wire:submit.prevent="submit">
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
                            <td> {{ $itemReturPenjualan->mutasiStock->stock->produk->nama }} </td>
                            <td> {{ $itemReturPenjualan->mutasiStock->stock->kode_batch }} </td>
                            <td class="text-end"> {{ -$itemReturPenjualan->mutasiStockPenjualan->jumlah }} </td>
                            <td>
                                <x-input-livewire-numeric
                                        wire:key="input-{{ $key }}"
                                        inline small
                                        :label='__("application.quantity") . " " . $itemReturPenjualan->mutasiStock->stock->produk->nama ?? null . " batch " . $itemReturPenjualan->mutasiStock->stock->kode_batch ?? null'
                                        wire:model='itemReturPenjualans.{{ $key }}.jumlah'
                                />
                            </td>
                            <td>
                                <label for="alasan_{{ $key }}" class="visually-hidden">
                                    {{ __("application.alasan") . " " . ($itemReturPenjualan->mutasiStock->stock->produk->nama ?? null) . " batch " . ($itemReturPenjualan->mutasiStock->stock->kode_batch ?? null) }}
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



            </form>





        </div>
    </div>

</article>
