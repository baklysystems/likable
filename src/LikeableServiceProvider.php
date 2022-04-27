<?php

namespace AmrNRD\Likeable;

use Illuminate\Support\ServiceProvider;

class LikeableServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->publishes([ __DIR__ . '/../config/like.php' => config_path('like.php')], 'config');

        $this->publishes([ __DIR__ . '/../migrations/' => database_path('migrations')], 'migrations');

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../migrations/');
        }

        $this->mergeConfigFrom( __DIR__ . '/../config/like.php', 'like');
    }
}
