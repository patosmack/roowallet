<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {

            $table->engine = "InnoDB";

            $table->increments('id');
            $table->unsignedInteger('user_id')->unique();
            $table->unsignedInteger('wallet_currency_id');
            $table->decimal('funds', 13, 4);
            $table->dateTime('funds_update');


            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('wallet_currency_id')->references('id')->on('wallet_currencies');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('wallets');
    }
}
