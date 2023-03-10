<?php

namespace App\Providers;

use App\Services\BookService;
use App\Services\Interfaces\BookServiceInterface;
use Illuminate\Support\ServiceProvider;

class ServiceLayerProvider extends ServiceProvider
{

    public $bindings = [
        BookServiceInterface::class => BookService::class,
    ];
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

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
