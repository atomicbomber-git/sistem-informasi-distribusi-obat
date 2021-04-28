<x-layouts.app>
    <x-feature-title>
        <i class="bi-box"></i>
        @lang("application.create")
    </x-feature-title>

    <x-breadcrumb>
        <li class="breadcrumb-item">
            <a href="{{ route('produk.index') }}">
                @lang("application.product")
            </a>
        </li>

        <li class="breadcrumb-item active"
            aria-current="page"
        >
            @lang("application.create")
        </li>
    </x-breadcrumb>

    <x-messages></x-messages>

    <div class="card">
        <div class="card-body">
            <x-form-post :action="route('produk.store')"
                         id="the-form"
            >
                <x-input
                        field="nama"
                        :label="__('application.name')"
                />

                <x-input
                        field="satuan"
                        :label="__('application.unit')"
                />

                <x-input
                        field="kode"
                        :label="__('application.code')"
                />

                <x-input
                        x-data="{}"
                        x-init="new Cleave($el, { numeral: true, swapHiddenInput: true })"
                        field="harga_satuan"
                        :label="__('application.unit_price')"
                />

                <x-textarea
                        field="deskripsi"
                        :label="__('application.description')"
                />
            </x-form-post>
        </div>
        <x-card-footer-submit>
            <x-submit-button
                    form="the-form"
            >
                @lang("application.create")
                <x-icon-create/>
            </x-submit-button>
        </x-card-footer-submit>
    </div>
</x-layouts.app>