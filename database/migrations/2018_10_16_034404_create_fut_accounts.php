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
