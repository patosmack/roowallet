**RooWallet**
========
Wallet Package for **Laravel** that allows you to maintain credits for your users.

Installation
============
To install the package, include the following in your composer.json.
```
composer require patosmack/roowallet
```

And then include the following service provider in your app.php.

```
Patosmack\RooWallet\Providers\RooWalletServiceProvider::class,
```

Also add Alias.
```
'RooWallet' => Patosmack\RooWallet\Facades\RooWallet::class,
```


Lastly, publish the config.
```
php artisan vendor:publish --provider="Patosmack\RooWallet\Providers\RooWalletServiceProvider"
```
**NOTE: Remove database Migrations Files before publising**

> - *create_wallet_currencies_table.php*
> - *create_wallets_table.php*
> - *create_wallet_transactions_table.php*


Methods
=======


----------


*Currency*
--------

```
getCurrencyList()
```

> - **Response**: *Currency iso Array*

```
getCurrency($iso)
```
> - **Response**: *WalletCurrency Model or null*

```
addCurrency($iso, $name, $symbol, $conversion_rate, $enabled = 0)
```
> - **Response**: *True or False*

```
updateCurrency($iso, $name, $symbol, $conversion_rate, $enabled = 0)
```
> - **Response**: *True or False*

*Wallet*
------

```
getWallet($user_id)
```
> - **Response**: *Wallet Model or null*

```
createWallet($user_id, $currency_iso)
```
> - **Response**: *True or False*



*Transaction*
-----------

```
getTransactions($user_id)
```
> - **Response**: *WalletTransaction Model or array()*


```
funds($user_id)
```
> - **Response**: *User balance -> decimal(13, 4)*

```
deposit($user_id, $amount, $refence_id = null, $reference_description = null, $token = '')
```
> - **Response**: *True or False*


```
canWithdraw($user_id, $amount)
```
> - **Response**: *True or False*

```
withdraw($user_id, $amount, $refence_id = null, $reference_description = null, $token = '')
```
> - **Response**: *True or False*


```
getCredits($user_id)
```
> - **Response**: *decimal(13, 4)*

```
getDebits($user_id)
```
> - **Response**: *decimal(13, 4)*