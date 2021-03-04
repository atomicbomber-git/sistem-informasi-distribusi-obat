@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-box-arrow-in-right"></i>
                        {{ __('application.login') }}
                    </div>

                    <div class="card-body">
                        <x-form-post id="the-form">
                            <x-input
                                    field="username"
                                    label="{{ __('application.username') }}"
                            />

                            <x-input
                                    field="password"
                                    label="{{ __('application.password') }}"
                                    type="password"
                            />
                        </x-form-post>
                    </div>

                    <div class="card-footer d-flex justify-content-end">
                        <x-submit-button
                            form="the-form"
                        >
                            @lang("application.log_in")
                            <i class="bi-box-arrow-in-right"></i>
                        </x-submit-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
