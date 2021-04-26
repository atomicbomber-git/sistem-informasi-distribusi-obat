<x-layouts.app>
    <x-feature-title>
        <x-icon-edit/>
        @lang("application.edit")
    </x-feature-title>

    <x-breadcrumb>
        <li class="breadcrumb-item">
            <a href="{{ route('pemasok.index') }}">
                @lang("application.supplier")
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
            <x-form-post :action="route('pemasok.update', $pemasok)"
                         id="the-form"
            >
                @method("PATCH")

                <x-input
                        field="nama"
                        :label="__('application.name')"
                        :value="$pemasok->nama"
                />

                <x-textarea
                        field="alamat"
                        :label="__('application.address')"
                        :value="$pemasok->alamat"
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