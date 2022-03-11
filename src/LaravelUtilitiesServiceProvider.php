<?php

namespace Cyaoz94\LaravelUtilities;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class LaravelUtilitiesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(Filesystem $filesystem)
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-utilities');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-utilities');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('laravel-utilities.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../database/migrations/create_admin_users_table.php.stub' => $this->getMigrationFileName($filesystem, 'admin_users'),
            ], 'migrations');

            $this->publishes([
                __DIR__ . '/../database/seeders/RolePermissionSeeder.php' => database_path('seeders/RolePermissionSeeder.php'),
            ], 'seeders');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-utilities'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-utilities'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-utilities'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-utilities');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-utilities', function () {
            return new LaravelUtilities;
        });
    }

    protected function getMigrationFileName(Filesystem $filesystem, $name): string
    {
        $timestamp = date('Y_m_d_His');

        return Collection::make($this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $name) {
                return $filesystem->glob($path . '*_create_' . $name . '_table.php');
            })->push($this->app->databasePath() . "/migrations/{$timestamp}_create_".$name."_table.php")
            ->first();
    }
}
