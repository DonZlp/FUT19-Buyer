<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFutPlayers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('xb_lowest_bin')->nullable();
            $table->bigInteger('xb_buy_bin')->nullable();
            $table->bigInteger('xb_sell_bid')->nullable();
            $table->bigInteger('xb_sell_bin')->nullable();
            $table->bigInteger('ps_lowest_bin')->nullable();
            $table->bigInteger('ps_buy_bin')->nullable();
            $table->bigInteger('ps_sell_bid')->nullable();
            $table->bigInteger('ps_sell_bin')->nullable();
            $table->enum('auto_pricing', ['0', '1'])->default('1');
            $table->dateTime('last_price_update')->nullable();
            $table->dateTime('last_searched')->nullable();
            $table->bigInteger('futbin_id')->nullable();
            $table->bigInteger('base_id')->nullable();
            $table->bigInteger('resource_id')->nullable();
            $table->string('name');
            $table->integer('rating');
            $table->integer('league_id');
            $table->integer('club_id');
            $table->integer('nation_id');
            $table->string('position')->nullable();
            $table->bigInteger('total_searches')->default('0');
            $table->bigInteger('auctions_found')->default('0');
            $table->bigInteger('auctions_won')->default('0');
            $table->bigInteger('auctions_failed')->default('0');
            $table->enum('status', ['0', '1'])->default('1');
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
        Schema::dropIfExists('players');
    }
}
