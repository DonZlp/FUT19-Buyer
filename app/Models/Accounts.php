<?php
/**
 * Created by PhpStorm.
 * User: curtiscrewe
 * Date: 17/10/2018
 * Time: 02:44
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accounts extends Model
{

    use CrudTrait, SoftDeletes;

    protected $table = 'accounts';

    protected $fillable = [
        'in_use',
        'email',
        'password',
        'platform',
        'dob',
        'phishingToken',
        'backup_codes',
        'code_method',
        'sessionId',
        'personaId',
        'personaName',
        'clubName',
        'nucleusId',
        'tradepile_limit',
        'tradepile_cards',
        'tradepile_value',
        'coins',
        'status',
        'status_reason',
        'cooldown',
        'cooldown_activated',
        'last_sell_transaction',
        'last_login'
    ];

    public $timestamps = true;

    public function players()
    {
        return $this->hasMany(Players::class)->withTrashed();
    }

    public function transactions()
    {
        return $this->hasMany(Transactions::class);
    }

}