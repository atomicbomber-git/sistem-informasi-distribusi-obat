<x-layouts.app>
    <x-feature-title>
        <x-icon-user/>
        @lang("application.admin_user")
    </x-feature-title>

    <x-breadcrumb>
        <li class="breadcrumb-item active"
            aria-current="page"
        >
            @lang("application.admin_user")
        </li>
    </x-breadcrumb>

    <x-messages></x-messages>

    <div class="card">
        <div class="card-body">
            <x-form-patch :action="route('admin.user.update')" id="the-form">
                <x-input
                        field="name"
                        :label="__('application.name')"
                        :value="$user->name"/>

                <x-input
                        field="username"
                        :label="__('application.username')"
                        :value="$user->username"/>

                <x-input
                        :help="__('application.dont_fill_password_field_if_dont_wanna_change_password')"
                        field="password"
                        :label="__('application.password')"
                />

                <x-input
                        field="password_confirmation"
                        :label="__('application.password_confirmation')"
                />
            </x-form-patch>
        </div>

        <x-card-footer-submit>
            <x-submit-button
                    form="the-form"
            >
                @lang("application.update")
                <x-icon-edit/>
            </x-submit-button>
        </x-card-footer-submit>
    </div>
</x-layouts.app>