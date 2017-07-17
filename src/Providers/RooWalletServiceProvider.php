<?php namespace Patosmack\RooWallet\Providers;

use Illuminate\Support\ServiceProvider;


class RooWalletServiceProvider extends ServiceProvider
{

    public function boot()
    {
    }

    public function register()
    {

        $this->prepareResources();

        $this->app->singleton('RooWallet', 'Patosmack\RooWallet\RooWallet');

//        $this->app->singleton('RooWallet', function(){
//            return new RooWallet();
//        });
    }

    protected function prepareResources()
    {
        // Publish config
        $config = realpath(__DIR__.'/../config/config.php');

        $this->mergeConfigFrom($config, 'patosmack.roowallet');

        $this->publishes([
            $config => config_path('patosmack.roowallet.php'),
        ], 'config');
        
        // Publish migrations
        $migrations = realpath(__DIR__.'/../migrations');

        $this->publishes([
            $migrations => $this->app->databasePath().'/migrations',
        ], 'migrations');
    }

}