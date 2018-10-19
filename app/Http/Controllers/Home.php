<?php
/**
 * Created by PhpStorm.
 * User: curtiscrewe
 * Date: 18/10/2018
 * Time: 16:27
 */

namespace App\Http\Controllers;

class Home extends Controller
{

    public function index()
    {
        return view('welcome');
    }

}