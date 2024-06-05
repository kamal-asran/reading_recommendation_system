<?php
namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseFromRequest;

class LoginRequest extends BaseFromRequest
{

    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:5',
        ];
    }
}
