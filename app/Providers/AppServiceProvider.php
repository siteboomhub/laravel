<?php

namespace App\Providers;

use App\Services\League\Classes\LeagueManager;
use App\Services\League\Repositories\LeagueCacheRepository;
use App\Services\League\Repositories\LeagueRepository;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Cache;
use Illuminate\Support\Facades\App;
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
        //
        $this->app->bind('league', LeagueManager::class);
        $this->app->bind(LeagueRepository::class, function($app){
            return new LeagueCacheRepository($app->get(Repository::class));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
