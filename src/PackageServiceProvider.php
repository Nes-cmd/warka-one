<?php

namespace YourVendor\YourPackage;

use Illuminate\Support\ServiceProvider;

class PackageServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register package services
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'your-package'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load package resources
        if ($this->app->runningInConsole()) {
            // Publish config
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('your-package.php'),
            ], 'config');
            
            // Publish migrations
            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations'),
            ], 'migrations');
        }
        
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        
        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'your-package');
    }
}