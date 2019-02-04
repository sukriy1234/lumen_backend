<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\UserService;
use App\Services\ProductService;
use App\Services\OrderService;

class CustomServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $this->app->bind('App\Services\UserService', function ($app) {
            return new UserService();
        });
        $this->app->bind('App\Services\ProductService', function ($app) {
            return new ProductService();
        });
        $this->app->bind('App\Services\OrderService', function ($app) {
            return new OrderService();
        });
    }
}
