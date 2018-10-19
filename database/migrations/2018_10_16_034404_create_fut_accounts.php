<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFutAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * CREATE TABLE `fut_accounts` (
    `id` int(11) NOT NULL,
    `in_use` enum('0','1') NOT NULL DEFAULT '0',
    `email` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `platform` varchar(255) NOT NULL,
    `dob` varchar(255) DEFAULT NULL,
    `phishingToken` varchar(255) DEFAULT NULL,
    `backup_codes` text,
    `code_method` enum('0','1') NOT NULL DEFAULT '0',
    `sessionId` varchar(255) DEFAULT NULL,
    `personaId` varchar(255) DEFAULT NULL,
    `personaName` varchar(255) DEFAULT NULL,
    `clubName` varchar(255) DEFAULT NULL,
    `nucleusId` varchar(255) DEFAULT NULL,
    `tradepile_cards` int(11) NOT NULL DEFAULT '0',
    `tradepile_value` int(15) NOT NULL DEFAULT '0',
    `coins` int(11) NOT NULL DEFAULT '0',
    `status` enum('-1','0','1') NOT NULL DEFAULT '1',
    `status_reason` varchar(255) DEFAULT NULL,
    `cooldown` enum('0','1') NOT NULL DEFAULT '0',
    `cooldown_activated` datetime DEFAULT NULL,
    `last_sell_transaction` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
    `last_update` datetime DEFAULT NULL,
    `last_login` datetime DEFAULT NULL,
    `endpoint` enum('0','1') NOT NULL DEFAULT '0'
    ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('in_use', ['0', '1'])->default('0');
            $table->string('email');
            $table->string('password');
            $table->string('platform');
            $table->string('dob')->nullable();
            $table->string('phishingToken')->nullable();
            $table->string('backup_codes')->nullable();
            $table->enum('code_method', ['0', '1'])->default('0');
            $table->string('sessionId')->nullable();
            $table->string('personaId')->nullable();
            $table->string('personaName')->nullable();
            $table->string('clubName')->nullable();
            $table->string('nucleusId')->nullable();
            $table->integer('tradepile_cards')->nullable();
            $table->integer('tradepile_value')->nullable();
            $table->integer('coins')->nullable();
            $table->enum('status', ['-1','0', '1'])->default('1');
            $table->string('status_reason')->nullable();
            $table->enum('cooldown', ['0', '1'])->default('0');
            $table->dateTime('cooldown_activated')->nullable();
            $table->dateTime('last_sell_transaction')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
