<?php
/**
 * Created by PhpStorm.
 * User: curtiscrewe
 * Date: 17/10/2018
 * Time: 02:50
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Players extends Model
{

    use CrudTrait, SoftDeletes;

    protected $table = 'players';

    protected $fillable = [
        'xb_lowest_bin',
        'xb_buy_bin',
        'xb_sell_bid',
        'xb_sell_bin',
        'ps_lowest_bin',
        'ps_buy_bin',
        'ps_sell_bid',
        'ps_sell_bin',
        'auto_pricing',
        'last_price_update',
        'last_searched',
        'futbin_id',
        'base_id',
        'resource_id',
        'name',
        'rating',
        'league_id',
        'club_id',
        'nation_id',
        'position',
        'total_searches',
        'auctions_found',
        'auctions_won',
        'auctions_failed',
        'status'
    ];

    public $timestamps = true;

    public function getProfitToday()
    {
        $transactions = Transactions::query()->where('player_id', $this->id)->whereDate('sold_time', Carbon::now()->toDateString())->get();
        $profit = 0;
        if(count($transactions) > 0) {
            foreach($transactions as $row) {
                $profit = $profit + (($row->sell_bin *0.95) - $row->buy_bin);
            }
        }
        return $profit;
    }

}