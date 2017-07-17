<?php

namespace Patosmack\RooWallet\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{

    protected $fillable = ['wallet_id', 'amount' , 'action', 'direction', 'type', 'reference_id', 'reference_description', 'token', 'deleted', 'delete_motive'];

    public function wallet()
    {
        return $this->belongsTo('Patosmack\RooWallet\Models\Wallet');
    }
}
