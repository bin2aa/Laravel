<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\Category;

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

        // Sử dụng View Composer để chia sẻ dữ liệu giữa các view
        View::composer(['client.*'], function ($view) {
            $categories = Category::withCount('posts')->get();
            $view->with('categories', $categories);
        });
    }
}
