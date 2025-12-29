<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\User;
use App\Models\Product;
use App\Models\Request;
use App\Observers\UserObserver;
use App\Observers\ProductObserver;
use App\Observers\RequestObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        
        // Register model observers for automatic activity logging
        User::observe(UserObserver::class);
        Product::observe(ProductObserver::class);
        Request::observe(RequestObserver::class);
    }
}
