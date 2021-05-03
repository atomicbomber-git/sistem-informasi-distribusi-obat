<article>
    <x-feature-title>
        <x-icon-create/>
        @lang("application.create")
    </x-feature-title>

    <x-breadcrumb>
        <li class="breadcrumb-item">
            <a href="{{ route("retur-penjualan.index") }}">
                @lang("application.sales-return")
            </a>
        </li>

        <li class="breadcrumb-item active"
            aria-current="page">
            @lang("application.create")
        </li>
    </x-breadcrumb>

    <form wire:submit.prevent="submit"
          class="card"
    >
        <div class="card-body">
            <x-input
                    livewire
                    field="returPenjualan.waktu_pengembalian"
                    :label="__('application.delivered_at')"
                    type="datetime-local"
            />

            @error("draftItemReturPenjualans")
            <div class="alert alert-danger">
                {{ $message }}
            </div>
            @enderror

            <x-table>
                <x-thead>
                    <tr>
                        <th> @lang("application.number_symbol") </th>
                        <th> @lang("application.product") </th>
                        <th> @lang("application.batch_code") </th>
                        <th class="text-end"> @lang("application.original_quantity") </th>
                        <th class="text-end"> @lang("application.quantity") </th>
                        <th> @lang("application.reason") </th>
                        <th class="text-center"> @lang("application.controls") </th>
                    </tr>
                </x-thead>

                <tbody>
                    @foreach ($draftItemReturPenjualans as $key => $draftItemReturPenjualan)
                        <tr wire:key="{{ $key }}">
                            <td> {{ $loop->iteration }} </td>
                            <td> {{ $draftItemReturPenjualan["nama_produk"] ?? null }} </td>
                            <td> {{ $draftItemReturPenjualan["kode_batch"] ?? null }} </td>
                            <td class="text-end"> {{ $draftItemReturPenjualan["jumlah_original"] ?? null }} </td>
                            <td>
                                <x-input-livewire-numeric
                                        wire:key="input-{{ $key }}"
                                        inline small
                                        :label='__("application.quantity") . " " . $draftItemReturPenjualan["nama_produk"] ?? null . " batch " . $draftItemReturPenjualan["kode_batch"] ?? null'
                                        wire:model='draftItemReturPenjualans.{{ $key }}.jumlah'
                                />
                            </td>
                            <td>
                                <label for="alasan_{{ $key }}" class="visually-hidden">
                                    {{ __("application.alasan") . " " . ($draftItemReturPenjualan["nama_produk"] ?? null) . " batch " . ($draftItemReturPenjualan["kode_batch"] ?? null) }}
                                </label>
                                <select
                                        class="form-select form-select-sm"
                                        wire:model="draftItemReturPenjualans.{{ $key }}.alasan"
                                        name="alasan_{{ $key }}"
                                        id="alasan_{{ $key }}"
                                >
                                    @foreach (\App\Http\Livewire\ReturPenjualanCreate::REASONS as $reason)
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
                    @lang("application.sales_invoice")
                </label>
                <select
                        wire:ignore
                        style="width: 100%"
                        x-data
                        x-init="
                    $($el).select2({
                        ajax: { url: '{{ route('faktur-penjualan.search') }}' },
                        theme: 'bootstrap-5'
                    }).change(e => {
                        if (!!e.target.value) {
                            $wire.set('faktur_penjualan_id', e.target.value)
                        }
                    })
"
                        id="sales-invoice-select"
                ></select>
            </div>

            <div wire:ignore>
                <label class="form-label"
                       for="item-add"
                >
                    @lang("application.add_item")
                </label>
                <select
                        wire:ignore
                        style="width: 100%"
                        x-data
                        x-init="
                        (function() {
                            let searchUrl = null

                            Livewire.on('fakturPenjualanChanged', (newSearchUrl) => {
                                searchUrl = newSearchUrl
                                console.log(searchUrl)
                            })

                            $($el).select2({
                                ajax: { url: () => searchUrl },
                                theme: 'bootstrap-5'
                            }).change(e => {
                                if (!!e.target.value) {
                                    $wire.call('addItem', e.target.value)
                                    $(e.target).val(null).trigger('change')
                                }
                            })
                        })()
"
                        id="item-add"
                ></select>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
            <button class="btn btn-primary">
                @lang("application.create")
                <x-icon-create/>
            </button>
        </div>
    </form>
</article>
