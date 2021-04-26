<?php

namespace App\Providers;

use App\Contracts\OccupationParser;
use App\Services\OnetOccupationParser;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerServices();
    }

    private function registerServices()
    {
        $this->app->singleton(OccupationParser::class, OnetOccupationParser::class);
    }
}
