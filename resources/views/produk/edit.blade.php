<x-layouts.app>
    <x-feature-title>
        <i class="bi-box"></i>
        {{ $produk->nama }}
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
            @lang("application.edit")
        </li>
    </x-breadcrumb>

    <x-messages></x-messages>

    <div class="card">
        <div class="card-body">
            <x-form-patch :action="route('produk.update', $produk)"
                          id="the-form"
            >
                <x-input
                        field="nama"
                        :label="__('application.name')"
                        :value="$produk->nama"
                />

                <x-input
                        field="kode"
                        :label="__('application.code')"
                        :value="$produk->kode"
                />

                <x-textarea
                        field="deskripsi"
                        :label="__('application.description')"
                        :value="$produk->deskripsi"
                />
            </x-form-patch>
        </div>
        <x-card-footer-submit>
            <x-submit-button
                    form="the-form"
            >
                @lang("application.update")
                <i class="bi-pencil"></i>
            </x-submit-button>
        </x-card-footer-submit>
    </div>
</x-layouts.app>