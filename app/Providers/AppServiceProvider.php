<?php

namespace App\Providers;

use App\Availability;
use App\Http\Controllers\MailController;
use App\Models\Settings;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
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
        $this->app->singleton(Availability::class, function ($app) {
            return new Availability();
        });

        $this->app->bind(MailController::class, function($app) {
            return new MailController();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);


        $settings = Cache::remember('settings', 60, function () {
            return Settings::pluck('value', 'name')->all();
        });


        config()->set('mail', array_merge(config('mail'), [
                'smtp' => [
                    'transport' => 'smtp',
                    'host' => env('MAIL_HOST', $settings['mail_server']),
                    'port' => env('MAIL_PORT', $settings['mail_port']),
                    'encryption' => env('MAIL_ENCRYPTION', $settings['mail_encryption']),
                    'username' => env($settings['mail_account']),
                    'password' => env($settings['mail_password']),
                    'timeout' => null,
                    'auth_mode' => null,
                ],
            'from' => [
                'address' => $settings['mail_account'],
                'name' => $settings['mail_name']
            ]
        ]));
    }
}
