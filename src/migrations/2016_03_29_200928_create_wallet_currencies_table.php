<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_currencies', function (Blueprint $table) {

            $table->engine = "InnoDB";

            $table->increments('id');
            $table->string('name');
            $table->string('iso');
            $table->string('symbol');
            $table->string('conversion_rate');
            $table->boolean('enabled')->default(0);
            $table->timestamps();
        });

        $currency_codes = \Patosmack\RooWallet\Facades\RooWallet::getCurrencyList();
        foreach ($currency_codes as $code){
            $wallet_currency = new \Patosmack\RooWallet\Models\WalletCurrency();
            $wallet_currency->iso = $code;
            $wallet_currency->name = '';
            $wallet_currency->symbol = '';
            $wallet_currency->conversion_rate = 1;
            $wallet_currency->save();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('wallet_currencies');
    }
}
