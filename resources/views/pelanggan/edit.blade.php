<x-layouts.app>
    <x-feature-title>
        <x-icon-edit/>
        @lang("application.edit")
    </x-feature-title>

    <x-breadcrumb>
        <li class="breadcrumb-item">
            <a href="{{ route('pelanggan.index') }}">
                @lang("application.customer")
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
            <x-form-post :action="route('pelanggan.update', $pelanggan)"
                         id="the-form"
            >
                @method("PATCH")

                <x-input
                        field="nama"
                        :label="__('application.name')"
                        :value="$pelanggan->nama"
                />

                <x-textarea
                        field="alamat"
                        :label="__('application.address')"
                        :value="$pelanggan->alamat"
                />
            </x-form-post>
        </div>
        <x-card-footer-submit>
            <x-submit-button
                    form="the-form"
            >
                @lang("application.edit")
                <x-icon-edit/>
            </x-submit-button>
        </x-card-footer-submit>
    </div>
</x-layouts.app>