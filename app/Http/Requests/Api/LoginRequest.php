<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Api\ApiFormRequest;

class LoginRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }
} 