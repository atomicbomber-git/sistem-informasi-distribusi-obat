<article>
    <x-feature-title>
        <x-icon-return/>
        @lang("application.return")
    </x-feature-title>

    <x-breadcrumb>
        <li class="breadcrumb-item">
            <a href="{{ route("faktur-penjualan.index") }}">
                @lang("application.sales_invoice")
            </a>
        </li>

        <li class="breadcrumb-item">
            {{ $fakturPenjualan->getNomor() }}
        </li>

        <li class="breadcrumb-item active"
            aria-current="page"
        >
            @lang("application.return")
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
                        <th class="text-end"> @lang("application.quantity") </th>
                        <th> @lang("application.reason") </th>
                        <th class="text-center"> @lang("application.controls") </th>
                    </tr>
                </x-thead>

                <tbody>
                    @foreach ($draftItemReturPenjualans as $key => $draftItemReturPenjualan)
                        <tr wire:key="{{ $key }}">
                            <td> {{ $loop->iteration }} </td>
                            <td> {{ $draftItemReturPenjualan["mutasiStock"]["stock"]["produk"]["nama"] }} </td>
                            <td> {{ $draftItemReturPenjualan["mutasiStock"]["stock"]["kode_batch"] }} </td>
                            <td>
                                <x-input-livewire-numeric
                                        inline small
                                        :label='__("application.quantity") . " " . $draftItemReturPenjualan["mutasiStock"]["stock"]["produk"]["nama"] . " batch " . $draftItemReturPenjualan["mutasiStock"]["stock"]["kode_batch"]'
                                        wire:model.lazy='draftItemReturPenjualans.{{ $key }}.jumlah'
                                />
                            </td>
                            <td>
                                <label for="alasan_{{ $key }}" class="visually-hidden">
                                    {{ __("application.alasan") . " " . $draftItemReturPenjualan["mutasiStock"]["stock"]["produk"]["nama"] . " batch " . $draftItemReturPenjualan["mutasiStock"]["stock"]["kode_batch"] }}
                                </label>
                                <select
                                        class="form-select form-select-sm"
                                        wire:model.lazy="draftItemReturPenjualans.{{ $key }}.alasan"
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
                    @lang("application.add_item")
                </label>
                <select
                        wire:ignore
                        style="width: 100%"
                        x-data
                        x-init="
                    $($el).select2({
                        ajax: { url: '{{ route('faktur-penjualan.search-item', $fakturPenjualan) }}' },
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
        <div class="card-footer d-flex justify-content-end">
            <button class="btn btn-primary">
                @lang("application.create")
                <x-icon-create/>
            </button>
        </div>

    </form>
</article>