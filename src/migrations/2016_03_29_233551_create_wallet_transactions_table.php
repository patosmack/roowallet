<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletTransactionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {

            $table->engine = "InnoDB";

            $table->increments('id');

            $table->unsignedInteger('wallet_id');

            $table->decimal('amount', 13, 4);

            $table->string('action');
            $table->string('direction');
            $table->string('type');

            $table->string('reference_id')->nullable();
            $table->string('reference_description')->nullable();

            $table->string('token');
            $table->boolean('deleted')->default(0);
            $table->string('delete_motive')->nullable();


            $table->foreign('wallet_id')->references('id')->on('wallets')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::drop('wallet_transactions');
    }
}
