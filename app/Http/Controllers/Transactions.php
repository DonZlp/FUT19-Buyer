<?php
/**
 * Created by PhpStorm.
 * User: curtiscrewe
 * Date: 18/10/2018
 * Time: 16:36
 */

namespace App\Http\Controllers;

use Backpack\CRUD\app\Http\Controllers\CrudController;

class Transactions extends CrudController
{

    public function setup() {
        $this->crud->setModel("App\Models\Transactions");
        $this->crud->setRoute("admin/transactions");
        $this->crud->setEntityNameStrings('Transactions', 'Transaction');
        $this->crud->removeAllButtons();
        $this->crud->setColumns([
            'id',
            [
                'label' => 'Player',
                'type' => 'select',
                'name' => 'player_id',
                'entity' => 'player',
                'attribute' => 'name',
                'model' => 'App\Models\Players'
            ],
            [
                'label' => 'Account',
                'type' => 'select',
                'name' => 'account_id',
                'entity' => 'account',
                'attribute' => 'email',
                'model' => 'App\Models\Accounts'
            ],
            [
                'name' => 'buy_bin',
                'label' => 'Buy Bin'
            ],
            [
                'name' => 'sell_bin',
                'label' => 'Sell Bin'
            ],
            [
                'label' => 'Profit',
                'type' => 'closure',
                'function' => function($entry) {
                    return ((is_null($entry->sold_time) ? $entry->listed_at : $entry->sell_bin) *0.95) - $entry->buy_bin;
                }
            ],
            [
                'name' => 'listed_time',
                'label' => 'Listed Time'
            ],
            [
                'name' => 'sold_time',
                'label' => 'Sold Time'
            ],
            [
                'label' => 'Status',
                'type' => 'closure',
                'function' => function($entry) {
                    return (is_null($entry->sold_time) ? "<label class='label label-info'>Selling</label>" : "<label class='label label-success'>Sold</label>");
                }
            ]
        ]);
        $this->crud->addFilter([
            'type' => 'date_range',
            'name' => 'from_to',
            'label'=> 'Sold Date'
        ], false, function($value) {
            $dates = json_decode($value);
            $this->crud->addClause('whereDate', 'sold_time', '>=', $dates->from);
            $this->crud->addClause('whereDate', 'sold_time', '<=', $dates->to);
        });
        $this->crud->addFilter([
            'name' => 'status',
            'type' => 'dropdown',
            'label'=> 'Status'
        ], [
            1 => 'Selling',
            2 => 'Sold'
        ], function($value) {
            if($value === 1) {
                $this->crud->addClause('whereNull', 'sold_time');
            } else {
                $this->crud->addClause('whereNotNull', 'sold_time');
            }
        });
    }

}