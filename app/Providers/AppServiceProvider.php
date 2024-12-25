<?php

namespace App\Providers;

use App\Storages\LocalStorage;
use App\Storages\S3Storage;
use App\Storages\StorageInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(StorageInterface::class, function ($app) {
            return new LocalStorage();
            // return new S3Storage();
        });
    }
}
