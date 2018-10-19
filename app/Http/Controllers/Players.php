<?php
/**
 * Created by PhpStorm.
 * User: curtiscrewe
 * Date: 18/10/2018
 * Time: 02:23
 */

namespace App\Http\Controllers;

use App\Http\Requests\Players as StoreRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Helpers\FUTBIN;

class Players extends CrudController
{

    public function setup() {
        $this->crud->setModel("App\Models\Players");
        $this->crud->setRoute("admin/players");
        $this->crud->setEntityNameStrings('Players', 'Player');
        $this->crud->removeButton('update');
        $this->crud->setColumns([
            [
                'name' => 'name',
                'label' => 'Player'
            ],
            [
                'name' => 'ps_buy_bin',
                'label' => 'PS Buy'
            ],
            [
                'name' => 'ps_sell_bin',
                'label' => 'PS Sell'
            ],
            [
                'name' => 'xb_buy_bin',
                'label' => 'Xbox Buy'
            ],
            [
                'name' => 'xb_sell_bin',
                'label' => 'Xbox Sell'
            ],
            [
                'label' => 'Today Profit',
                'type' => 'model_function',
                'function_name' => 'getProfitToday'
            ],
            [
                'name' => 'last_price_update',
                'label' => 'Last Update'
            ]
        ]);
        $this->crud->addField([
            'name' => 'player_search',
            'label' => 'Player Name',
            'attributes' => [
                'placeholder' => 'Start to type for the dropdown to appear',
                'id' => 'player_search'
            ]
        ]);
        $this->crud->addField([
            'name' => 'futbin_id',
            'type' => 'hidden'
        ]);
        $this->crud->addField([
            'name' => 'base_id',
            'type' => 'hidden'
        ]);
        $this->crud->addField([
            'name' => 'resource_id',
            'type' => 'hidden'
        ]);
        $this->crud->addField([
            'name' => 'name',
            'type' => 'hidden'
        ]);
        $this->crud->addField([
            'name' => 'position',
            'type' => 'hidden'
        ]);
        $this->crud->addField([
            'name' => 'rating',
            'type' => 'hidden'
        ]);
        $this->crud->addField([
            'name' => 'league_id',
            'type' => 'hidden'
        ]);
        $this->crud->addField([
            'name' => 'club_id',
            'type' => 'hidden'
        ]);
        $this->crud->addField([
            'name' => 'nation_id',
            'type' => 'hidden'
        ]);
    }

    public function find() {
        $futbin = new FUTBIN("PS");
        $players_array = [];
        $players = $futbin->getPlayers(urlencode(request('term')));
        if(count($players['data']) > 0) {
            foreach($players['data'] as $player) {
                $players_array[] = [
                    "futbin_id" => $player['ID'],
                    "short_name" => $player['playername'],
                    "position" => $player['position'],
                    "base_id" => $player['playerid'],
                    "resource_id" => $player['resource_id'],
                    "club" => $player['club'],
                    "nation" => $player['nation'],
                    "league" => $player['league'],
                    "rating" => $player['rating'],
                    "revision_type" => $player['raretype']
                ];
            }
        }
        $array = $this->unique_multidim_array($players_array, "resource_id");
        $sortArray = [];
        foreach($array as $person){
            foreach($person as $key=>$value){
                if(!isset($sortArray[$key])){
                    $sortArray[$key] = [];
                }
                $sortArray[$key][] = $value;
            }
        }
        $orderby = "rating";
        @array_multisort($sortArray[$orderby],SORT_DESC,$array);
        return json_encode($array, JSON_UNESCAPED_UNICODE);
    }

    public function store(StoreRequest $request) {
        return parent::storeCrud();
    }

    private function unique_multidim_array($array, $key) {
        $temp_array = array();
        $i = 0;
        $key_array = array();
        foreach($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }

}