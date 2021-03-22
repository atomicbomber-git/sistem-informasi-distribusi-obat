<?php

namespace App\Providers;

use Bezhanov\Faker\ProviderCollectionHelper;
use Faker\Generator as Faker;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        bcscale(4);

        $this->app->extend(Faker::class, function (Faker $faker) {
            ProviderCollectionHelper::addAllProvidersTo($faker);
            return $faker;
        });
    }
}
