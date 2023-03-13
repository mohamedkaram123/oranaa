<?php

namespace App\Providers;

use App\Repositories\Contract\ProductContract;
use App\Repositories\Contract\UserContract;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(UserContract::class,UserRepository::class);
        $this->app->bind(ProductContract::class,ProductRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
