<?php
/**
 * Created by PhpStorm.
 * User: curtiscrewe
 * Date: 19/10/2018
 * Time: 04:21
 */

namespace App\Http\Requests;

use App\Models\Accounts;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAccounts extends FormRequest {

    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'platform' => [
                'required',
                Rule::in(['XBOX', 'PS4', 'PC']),
            ],
            'email' => 'required|unique:accounts,email,'.$this->route('account'),
            'password' => 'required',
            'code_method' => 'required|numeric',
            'backup_codes' => 'required',
            'endpoint' => 'required|numeric',
            'in_use' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'platform.in' => 'Platform must be XBOX or PS',
            'email.required' => 'Email Address is required',
            'email.unique' => 'Email Address must be unique.'
        ];
    }

}