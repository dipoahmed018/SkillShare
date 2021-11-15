<?php

namespace App\Providers;

use App\Models\Catagory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $viewsForCatagories = ['Layout.Header'];

        View::composer($viewsForCatagories, function ($view) {
            $view->with('catagories', Catagory::all());
        });
        
        View::composer('*', function ($view) {
            $view->with('user', Auth::user());
        });
    }
}
