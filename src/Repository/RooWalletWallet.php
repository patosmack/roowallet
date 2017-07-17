<?php namespace Patosmack\RooWallet\Repository;
use Carbon\Carbon;
use Patosmack\RooWallet\Models\Wallet;
use Patosmack\RooWallet\Models\WalletTransaction;


/**
 * RooWallet Package
 * Digital Wallet for Laravel
 *
 * @author Patricio Alvarez
 *
 */
class RooWalletWallet
{

    protected $currencyRepo;

    function __construct($currency_repo) {
        $this->currencyRepo = $currency_repo;
    }

    public function getWallet($user_id){
        return Wallet::where('user_id',intval($user_id))->first();
    }

    public function createWallet($user_id, $currency_iso){
        $currency = $this->currencyRepo->getCurrency($currency_iso);
        if(!$currency) return false;
        $wallet = self::getWallet($user_id);
        if(!$wallet){
            $wallet = new Wallet();
            $wallet->user_id = $user_id;
            $wallet->wallet_currency_id = $currency->id;
            $wallet->funds = 0;
            $wallet->funds_update = Carbon::now();
            return $wallet->save();
        }
        return false;
    }


}