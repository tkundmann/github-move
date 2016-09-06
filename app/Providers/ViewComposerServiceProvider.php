<?php

namespace App\Providers;

use App\ViewComposers\HeaderComposer;
use App\ViewComposers\MenuComposer;
use App\ViewComposers\FooterComposer;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('partial.header', HeaderComposer::class);
        view()->composer('partial.menu', MenuComposer::class);
        view()->composer('partial.footer', FooterComposer::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
