<?php

namespace App\Providers;

use App\Repositories\FornecedorRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use FornecedorEloquentORM;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            FornecedorRepositoryInterface::class,
            FornecedorEloquentORM::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
