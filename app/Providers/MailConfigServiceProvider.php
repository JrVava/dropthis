<?php

namespace App\Providers;

use App\Models\SMTP;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\DB;
class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Config::get('mail.mailers.smtp');
        Config::get('mail.from');
        // dd(Config::get('mail.from'),Config::get('mail.mailers.smtp'));
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
