<?php

namespace App\Providers;

use API;
use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        API::error(function(ModelNotFoundException $exception)
        {
            return Response::create(['error' => 'Model not found.'], 404);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
