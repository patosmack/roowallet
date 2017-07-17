<?php

namespace Patosmack\RooWallet\Models;

use Illuminate\Database\Eloquent\Model;

class WalletCurrency extends Model
{

    protected $fillable = ['name', 'symbol' , 'conversion_rate'];

}
