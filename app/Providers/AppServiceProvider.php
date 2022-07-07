<?php

namespace App\Providers;

use App\Models\Cake;
use App\Models\WaitingList;
use App\Observers\CakeObserver;
use App\Observers\WaitingListObserver;
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
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Cake::observe(CakeObserver::class);
        WaitingList::observe(WaitingListObserver::class);
    }
}
