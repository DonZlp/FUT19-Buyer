<?php
/**
 * Created by PhpStorm.
 * User: curtiscrewe
 * Date: 19/10/2018
 * Time: 00:04
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAccounts extends FormRequest {

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
            'email' => 'max_accounts|required|unique:accounts,email',
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
            'email.unique' => 'Email Address must be unique.',
            'email.max_accounts' => 'System is currently limited to 1 account.'
        ];
    }

}