<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'sometimes|string|min:3|unique:users,username,' . $this->user()->id,
            'email' => 'sometimes|email|unique:users,email,' . $this->user()->id,
            'password' => 'sometimes|string|min:8|confirmed',
        ];
    }
}
