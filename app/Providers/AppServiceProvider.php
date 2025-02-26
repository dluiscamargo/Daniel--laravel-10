<?php

namespace App\Providers;

use App\Repositories\{FornecedorEloquentORM};
use App\Repositories\{FornecedorRepositoryInterface};
use App\Repositories\{RenaveEloquentORM};
use App\Repositories\{RenaveRepositoryInterface};
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            FornecedorRepositoryInterface::class,
            FornecedorEloquentORM::class,
            RenaveEloquentORM::class,
            RenaveRepositoryInterface::class
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
