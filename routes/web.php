<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Home@index');

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['admin', 'auth']
], function () {

    /**
     * Dashboard
     */
    Route::prefix('dashboard')->group(function () {
        Route::get('', 'Dashboard@main');
        Route::get('graphData', 'Dashboard@graphData')->name('dashboard.graphData');
    });

    /**
     * Crud Tables
     */
    CRUD::resource('accounts', 'Accounts');
    CRUD::resource('transactions', 'Transactions');
    CRUD::resource('players', 'Players')->with(function(){
        Route::get('players/find', 'Players@find');
    });

});