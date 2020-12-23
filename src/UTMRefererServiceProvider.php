<?php

namespace Abhij89\UTMReferer;

use Illuminate\Support\ServiceProvider;

class UTMRefererServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/utm-referer.php' => config_path('utm-referer.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/utm-referer.php', 'utm-referer');

        $this->app->when(UTMReferer::class)
            ->needs('$refererCookieKey')
            ->give(function () {
                return $this->app['config']->get('utm-referer.referer_cookie_key');
            });
	$this->app->when(UTMReferer::class)
            ->needs('$utmCookieKey')
            ->give(function () {
                return $this->app['config']->get('utm-referer.utm_cookie_key');
            });

        $this->app->when(UTMReferer::class)
            ->needs('$sources')
            ->give(function () {
                return $this->app['config']->get('utm-referer.sources', []);
            });

        $this->app->singleton(UTMReferer::class);
        $this->app->alias(UTMReferer::class, 'utm-referer');
    }
}
