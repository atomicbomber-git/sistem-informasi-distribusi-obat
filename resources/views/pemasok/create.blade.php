<x-layouts.app>
    <x-feature-title>
        <x-icon-create/>
        @lang("application.create")
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
            @lang("application.create")
        </li>
    </x-breadcrumb>

    <x-messages></x-messages>

    <div class="card">
        <div class="card-body">
            <x-form-post :action="route('pemasok.store')"
                         id="the-form"
            >
                <x-input
                        field="nama"
                        :label="__('application.name')"
                />

                <x-textarea
                        field="alamat"
                        :label="__('application.address')"
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