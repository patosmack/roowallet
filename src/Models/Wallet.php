<?php

namespace Patosmack\RooWallet\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{

    protected $fillable = ['user_id', 'wallet_currency_id' , 'funds', 'funds_update'];

    public function user()
    {
        $user_model = config('patosmack.roowallet.user_model');
        return $this->belongsTo($user_model);
    }

    public function walletCurrency()
    {
        return $this->belongsTo('Patosmack\RooWallet\Models\WalletCurrency');
    }

    public function walletTransactions()
    {
        return $this->hasMany('Patosmack\RooWallet\Models\WalletTransaction');
    }
}
