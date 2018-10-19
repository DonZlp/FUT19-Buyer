<?php
/**
 * Created by PhpStorm.
 * User: curtiscrewe
 * Date: 18/10/2018
 * Time: 03:19
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Players extends FormRequest {

    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'futbin_id' => 'required|numeric',
            'base_id' => 'required|numeric',
            'resource_id' => 'required|numeric',
            'name' => 'required',
            'position' => 'required',
            'rating' => 'required|numeric',
            'league_id' => 'required|numeric',
            'club_id' => 'required|numeric',
            'nation_id' => 'required|numeric'
        ];
    }

}