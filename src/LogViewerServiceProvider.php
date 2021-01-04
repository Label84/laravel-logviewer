<?php

namespace Label84\LogViewer;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Label84\LogViewer\Console\Commands\InstallLogViewer;

class LogViewerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/logviewer.php' => config_path('logviewer.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/logviewer'),
            ], 'views');

            $this->commands([
                InstallLogViewer::class,
            ]);
        }

        $this->registerRoutes();

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'logviewer');
    }

    public function register(): void
    {
        $this->app->bind('logviewer', function ($app) {
            return new LogViewer();
        });

        $this->mergeConfigFrom(
            __DIR__.'/../config/logviewer.php',
            'logviewer'
        );
    }

    protected function registerRoutes(): void
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    protected function routeConfiguration(): array
    {
        return [
            'prefix' => config('logviewer.route.prefix'),
            'middleware' => config('logviewer.route.middleware'),
        ];
    }
}
