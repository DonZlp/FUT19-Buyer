<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFutTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id');
            $table->integer('player_id');
            $table->bigInteger('card_id');
            $table->integer('buy_bin');
            $table->integer('sell_bin')->nullable();
            $table->integer('listed_bin')->nullable();
            $table->dateTime('bought_time')->nullable();
            $table->dateTime('listed_time')->nullable();
            $table->dateTime('sold_time')->nullable();
            $table->string('platform')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
