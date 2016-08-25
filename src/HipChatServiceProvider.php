<?php

namespace EvgeniyN\LaravelHipChat;

use EvgeniyN\LaravelHipChat\Connection\ConnectionFactory;
use Illuminate\Support\ServiceProvider;

class HipChatServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../views', 'hipchat');
        $this->mergeConfigFrom(__DIR__ . '/../config/hipchat.php', 'hipchat');

        $this->publishes([__DIR__ . '/../config' => config_path()], 'config');
        $this->publishes([__DIR__ . '/../views' => resource_path('views/vendor/hipchat')], 'views');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['hipchat'];
    }

    public function register()
    {
        $this->app->singleton('hipchat', function () {
            $factory = new ConnectionFactory($this->app['config']['hipchat']);

            return new HipChat($factory);
        });
    }
}