<?php

namespace App\Providers;

use App\Models\Accounts;
use Illuminate\Support\Facades\Validator;
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
        Validator::extend('max_accounts', function($attribute, $value, $parameters) {
            $accounts = Accounts::query()->whereNull('deleted_at')->count();
            return $accounts === 0;
        });
        date_default_timezone_set(config('app.timezone'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
