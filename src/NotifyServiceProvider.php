<?php

namespace Rcady\Notify;

use NotificationChannels\Twilio\TwilioConfig;
use Illuminate\Support\ServiceProvider;
use Twilio\Rest\Client as TwilioService;

class NotifyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(NotifyChannel::class)
            ->needs(Notify::class)
            ->give(function () {
                return new Notify(
                    $this->app->make(TwilioService::class),
                    $this->app->make(TwilioConfig::class)
                );
            });
    }
}
