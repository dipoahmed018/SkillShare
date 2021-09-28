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
        View::composer('Layout.Header',function($view){
            $catagories = Catagory::all();
            $view->with('catagories',$catagories);
            $view->with('user', Auth::user());
        });
    }
}
