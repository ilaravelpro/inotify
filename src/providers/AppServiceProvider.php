<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/19/20, 8:18 PM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\iNotify\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(inotify_path('config/inotify.php'), 'ilaravel.inotify');

        if($this->app->runningInConsole())
        {
            if (inotify('database.migrations.include', true))
                $this->loadMigrationsFrom(inotify_path('database/migrations'));
        }
    }

    public function register()
    {
        parent::register();
    }
}
