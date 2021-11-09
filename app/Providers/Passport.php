<?php
namespace App\Providers\Passport;
use Laravel\Passport\PassportServiceProvider;
class LumenPassportServiceProvider extends PassportServiceProvider
{
    /**
     * Setup the resource publishing groups for Passport.
     *
     * @return void
     */
    protected function offerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/passport.php' => app('config')->get('passport.php'),
            ], 'passport-config');
        }
    }
}
