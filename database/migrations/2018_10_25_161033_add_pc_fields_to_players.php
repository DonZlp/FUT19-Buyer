<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPcFieldsToPlayers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('players', function(Blueprint $table)
        {
            $table->bigInteger('pc_lowest_bin')->nullable();
            $table->bigInteger('pc_buy_bin')->nullable();
            $table->bigInteger('pc_sell_bid')->nullable();
            $table->bigInteger('pc_sell_bin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('players', function(Blueprint $table)
        {
            $table->dropColumn('pc_lowest_bin');
            $table->dropColumn('pc_buy_bin');
            $table->dropColumn('pc_sell_bid');
            $table->dropColumn('pc_sell_bin');
        });
    }
}
