<?php namespace Patosmack\RooWallet\Repository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Patosmack\RooWallet\Models\WalletTransaction;


/**
 * RooWallet Package
 * Digital Wallet for Laravel
 *
 * @author Patricio Alvarez
 *
 */
class RooWalletTransaction
{

    const ACTION_DEPOSIT = "DEPOSIT";
    const ACTION_WITHDRAW = "WITHDRAW";

    const DIRECTION_DEBIT = "DEBIT";
    const DIRECTION_CREDIT = "CREDIT";
    const DIRECTION_ADJUST = "ADJUST";

    const TYPE_AMOUNT = "AMOUNT";

    protected $currencyRepo;
    protected $walletRepo;

    function __construct($currency_repo, $wallet_repo) {
        $this->currencyRepo = $currency_repo;
        $this->walletRepo = $wallet_repo;
    }

    private function roundNumber($number){
        return round($number, 4);
    }

    public function getTransactions($user_id){
        $wallet = $this->walletRepo->getWallet(intval($user_id));
        if($wallet){
            return WalletTransaction::where('wallet_id',$wallet->id)->get();
        }
        return array();
    }

    public function calculateFunds($user_id){
        $wallet = $this->walletRepo->getWallet(intval($user_id));
        if($wallet){
            return self::calculateFundsbyWallet($wallet);
        }
        return 0;
    }

    private function saveFundsByWallet($wallet){
        $balance = self::calculateFundsbyWallet($wallet);
        $wallet->funds = $balance;
        $wallet->funds_update = Carbon::now();;
        return $wallet->save();
    }

    private function calculateFundsbyWallet($wallet){
        $credits = WalletTransaction::where('wallet_id',$wallet->id)->where('direction' , self::DIRECTION_CREDIT)->where('deleted', 0)->sum('amount');
        $debits = WalletTransaction::where('wallet_id',$wallet->id)->where('direction' , self::DIRECTION_DEBIT)->where('deleted', 0)->sum('amount');
        $balance = self::roundNumber($credits) - self::roundNumber($debits);
        return self::roundNumber($balance);
    }

    public function getCredits($user_id){
        $wallet = $this->walletRepo->getWallet(intval($user_id));
        if($wallet) {
            return WalletTransaction::where('wallet_id', $wallet->id)->where('direction', self::DIRECTION_CREDIT)->where('deleted', 0)->sum('amount');
        }
        return 0;
    }

    public function getDebits($user_id){
        $wallet = $this->walletRepo->getWallet(intval($user_id));
        if($wallet){
            return  self::roundNumber(WalletTransaction::where('wallet_id',$wallet->id)->where('direction' , self::DIRECTION_DEBIT)->where('deleted', 0)->sum('amount'));
        }
        return 0;
    }


    public function deposit($user_id, $amount, $refence_id = null, $reference_description = null, $token = ''){
        $wallet = $this->walletRepo->getWallet($user_id);

        if(!$wallet) return false;
        if($amount <= 0) return false;

        $transaction = new WalletTransaction();

        DB::transaction(function()  use ($transaction, $wallet, $amount, $refence_id, $reference_description, $token)
        {
            try{
                $transaction->wallet_id = $wallet->id;
                $transaction->amount = self::roundNumber($amount);
                $transaction->action = self::ACTION_DEPOSIT;
                $transaction->direction = self::DIRECTION_CREDIT;
                $transaction->type = self::TYPE_AMOUNT;
                $transaction->reference_id = $refence_id;
                $transaction->reference_description = $reference_description;
                $transaction->token = $token;

                $transaction->save();
                self::saveFundsByWallet($wallet);

            }
            catch(\Exception $e) {
                DB::rollback();
                throw $e;
            }
        });
        if($transaction->wallet_id > 0){
            return true;
        }
        return false;
    }

    private function testWithdraw($wallet, $amount){
        if(!$wallet) return false;
        if($amount <= 0) return false;

        $balance = self::calculateFundsbyWallet($wallet);

        $amount = self::roundNumber($amount);
        $left = self::roundNumber($balance - $amount);
        if($left >= 0) {
            return true;
        }
        return false;
    }

    public function canWithdraw($user_id, $amount){
        $wallet = $this->walletRepo->getWallet($user_id);
        if(!$wallet) return false;

        if($amount <= 0) return false;

        $amount = self::roundNumber($amount);
        $balance = self::calculateFundsbyWallet($wallet);
        $left = self::roundNumber($balance - $amount);


        if($left >= 0) {
            return true;
        }
        return false;
    }

    public function withdraw($user_id, $amount, $refence_id = null, $reference_description = null, $token = ''){
        $wallet = $this->walletRepo->getWallet($user_id);
        if(!$wallet) return false;

        if(self::testWithdraw($wallet, $amount)){

            $transaction = new WalletTransaction();

            DB::transaction(function()  use ($transaction, $wallet, $amount, $refence_id, $reference_description, $token)
            {
                try{
                    $transaction->wallet_id = $wallet->id;
                    $transaction->amount = self::roundNumber($amount);
                    $transaction->action = self::ACTION_WITHDRAW;
                    $transaction->direction = self::DIRECTION_DEBIT;
                    $transaction->type = self::TYPE_AMOUNT;
                    $transaction->reference_id = $refence_id;
                    $transaction->reference_description = $reference_description;
                    $transaction->token = $token;

                    $transaction->save();
                    self::saveFundsByWallet($wallet);

                }
                catch(\Exception $e) {
                    DB::rollback();
                    throw $e;
                }
            });
            if($transaction->wallet_id > 0){
                return true;
            }
        }
        return false;
    }

}