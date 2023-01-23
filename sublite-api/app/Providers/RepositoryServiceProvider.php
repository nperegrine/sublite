<?php

namespace App\Providers;

use App\Repository\SubscriberRepositoryInterface;
use App\Repository\FieldRepositoryInterface;
use App\Repository\Eloquent\SubscriberRepository;
use App\Repository\Eloquent\FieldRepository;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(RepositoryInterface::class, Repository::class);
        $this->app->bind(SubscriberRepositoryInterface::class, SubscriberRepository::class);
        $this->app->bind(FieldRepositoryInterface::class, FieldRepository::class);

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