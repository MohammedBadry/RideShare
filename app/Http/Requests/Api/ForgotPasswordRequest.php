<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Api\ApiFormRequest;

class ForgotPasswordRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
        ];
    }
} 