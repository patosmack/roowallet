<?php namespace Patosmack\RooWallet\Repository;

use InvalidArgumentException;
use Patosmack\RooWallet\Models\WalletCurrency;
use Patosmack\RooWallet\Models\WalletTransaction;


/**
 * RooWallet Package
 * Digital Wallet for Laravel
 *
 * @author Patricio Alvarez
 *
 */
class RooWalletCurrency
{
    protected $currencies_iso;

    function __construct($iso) {
        $this->currencies_iso = $iso;
    }

    /*
     *  Private Validations
     */

    private function validateIso($iso){
        $iso = strtoupper($iso);
        if(strlen($iso) != 3 or !in_array($iso, $this->currencies_iso)) throw new InvalidArgumentException('The iso code must be ISO 4217 Currency Codes');
        return $iso;
    }
    private function validateConversionRate($conversion_rate){
        if(!is_numeric($conversion_rate)) throw new InvalidArgumentException('The Conversion Rate must be a number');
        return $conversion_rate;
    }
    private function validateName($name){
        if(strlen($name) == 0) throw new InvalidArgumentException('The Name can not be empty');
        return $name;
    }
    private function validateSymbol($symbol){
        if(strlen($symbol) == 0) throw new InvalidArgumentException('The Symbol can not be empty');
        return $symbol;
    }

    /*
     *  Get ISO 4217 Currency Code List
     */

    public function getCurrencyList(){
        return $this->currencies_iso;
    }

    /*
     *   Get Curreny by Iso Code
     */

    public function getCurrency($iso){
        return WalletCurrency::whereIso(self::validateIso($iso))->first();;
    }

    /*
     *   Add a new Currency, if the currency Iso Code already exists, the old Currency Will be updated.
     */

    public function addCurrency($iso, $name, $symbol, $conversion_rate, $enabled = 0){
        $conversion_rate = self::validateConversionRate($conversion_rate);
        $name = self::validateName($name);
        $symbol = self::validateSymbol($symbol);

        $wallet_currency = self::getCurrency($iso);
        if(!$wallet_currency){
            $wallet_currency = new WalletCurrency();
            $wallet_currency->iso = $iso;
        }
        $wallet_currency->name = $name;
        $wallet_currency->symbol = $symbol;
        $wallet_currency->conversion_rate = $conversion_rate;
        $wallet_currency->enabled = $enabled;
        return $wallet_currency->save();
    }

    /*
    *   Update Currency, if the currency Iso Code.
    */

    public function updateCurrency($iso, $name, $symbol, $conversion_rate, $enabled = 0){
        $conversion_rate = self::validateConversionRate($conversion_rate);
        $name = self::validateName($name);
        $symbol = self::validateSymbol($symbol);

        $wallet_currency = self::getCurrency($iso);
        if(!$wallet_currency){
            return false;
        }
        $wallet_currency->name = $name;
        $wallet_currency->symbol = $symbol;
        $wallet_currency->conversion_rate = $conversion_rate;
        $wallet_currency->enabled = $enabled;
        return $wallet_currency->save();
    }

}