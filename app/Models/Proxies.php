<?php
/**
 * Created by PhpStorm.
 * User: curtiscrewe
 * Date: 17/10/2018
 * Time: 03:00
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Proxies extends Model
{

    use CrudTrait;

    protected $table = 'proxies';

    protected $fillable = [
        'ip',
        'port',
        'username',
        'password',
        'status'
    ];

}