<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
//use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Paginator;
//use Illuminate\Support\Facades\DB;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $smtp = \DB::table('smtps')->first();
        if ($smtp) {
            $config = array(
                'driver' => $smtp->mailer,
                'host' => $smtp->host,
                'port' => $smtp->port,
                'username' => $smtp->username,
                'password' => $smtp->password,
                'encryption' => $smtp->encryption,
              	'from' => ['address' => $smtp->reply_to,'name' => 'SMJ']
            );
            \Config::set('mail', $config);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Paginator::useBootstrap();
    }
}
