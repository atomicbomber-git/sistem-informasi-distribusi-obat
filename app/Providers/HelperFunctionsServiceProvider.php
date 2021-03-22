<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperFunctionsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        require(__DIR__ . "/helper_functions/bcmath_extensions.php");
    }
}
