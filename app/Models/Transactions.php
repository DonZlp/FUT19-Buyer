<?php
/**
 * Created by PhpStorm.
 * User: curtiscrewe
 * Date: 17/10/2018
 * Time: 02:58
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Transactions extends Model
{

    use CrudTrait;

    protected $table = 'transactions';

    protected $fillable = [
        'account_id',
        'player_id',
        'card_id',
        'buy_bin',
        'sell_bin',
        'listed_bin',
        'bought_time',
        'listed_time',
        'sold_time',
        'platform'
    ];

    public $timestamps = true;

    public function player()
    {
        return $this->hasOne(Players::class, 'id', 'player_id')->withTrashed();
    }

    public function account()
    {
        return $this->hasOne(Accounts::class, 'id', 'account_id')->withTrashed();
    }

}