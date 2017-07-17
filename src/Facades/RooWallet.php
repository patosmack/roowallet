<?php namespace Patosmack\RooWallet\Facades;

use Illuminate\Support\Facades\Facade;

class RooWallet extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'RooWallet';
    }
    
}