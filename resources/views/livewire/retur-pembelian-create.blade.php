<article>
    <x-feature-title>
        <x-icon-create/>
        @lang("application.create")
    </x-feature-title>

    <x-breadcrumb>
        <li class="breadcrumb-item">
            <a href="{{ route("retur-pembelian.index") }}">
                @lang("application.purchase-return")
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
                    livewire group
                    field="returPembelian.nomor"
                    :label="__('application.code')"
            >
                <x-slot name="input_prefix">
                    <span class="input-group-text"> {{ $returPembelian->getNomorPrefix() }}  </span>
                </x-slot>
            </x-input>

            <x-input
                    livewire
                    field="returPembelian.waktu_pengembalian"
                    :label="__('application.returned_at')"
                    type="datetime-local"
            />

            @error("itemReturPembelians")
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
                @foreach ($itemReturPembelians as $key => $itemReturPembelian)
                    <tr wire:key="{{ $key }}">
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $itemReturPembelian->itemFakturPembelian->produk->nama }} </td>
                        <td> {{ $itemReturPembelian->itemFakturPembelian->kode_batch }} </td>
                        <td class="text-end"> {{ \App\Support\Formatter::quantity($itemReturPembelian->itemFakturPembelian->jumlah) }} </td>
                        <td>
                            <x-input-livewire-numeric
                                    wire:key="input-{{ $key }}"
                                    inline small
                                    :label='__("application.quantity") . " " . $itemReturPembelian->itemFakturPembelian->produk->nama . " batch " . $itemReturPembelian->itemFakturPembelian->kode_batch ?? null'
                                    wire:model='itemReturPembelians.{{ $key }}.jumlah'
                            />
                        </td>
                        <td>
                            <label for="alasan_{{ $key }}" class="visually-hidden">
                                {{ __("application.alasan") . " " . ($itemReturPembelian->itemFakturPembelian->produk->nama) . " batch " . ($itemReturPembelian->itemFakturPembelian->kode_batch) }}
                            </label>
                            <select
                                    class="form-select form-select-sm"
                                    wire:model="itemReturPembelians.{{ $key }}.alasan"
                                    name="alasan_{{ $key }}"
                                    id="alasan_{{ $key }}"
                            >
                                @foreach (\App\Models\ItemReturPembelian::REASONS as $reason)
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

            <div wire:ignore class="mb-3">
                <label class="form-label"
                       for="item-add"
                >
                    @lang("application.purchase_invoice")
                </label>
                <select
                        wire:ignore
                        style="width: 100%"
                        x-data
                        x-init="
                    $($el).select2({
                        ajax: { url: '{{ route('faktur-pembelian.search') }}' },
                        theme: 'bootstrap-5'
                    }).change(e => {
                        if (!!e.target.value) {
                            $wire.set('returPembelian.faktur_pembelian_kode', e.target.value)
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
                        wire:key="add-item-select"
                        wire:ignore
                        style="width: 100%"
                        x-bind:disabled="searchUrl === null"
                        x-data="{ searchUrl: null }"
                        x-init="
                        (function() {
                            Livewire.on('fakturPembelianChanged', (newSearchUrl) => {
                                searchUrl = newSearchUrl
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
