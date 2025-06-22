<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiFormRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'error' => [
                    'message' => 'Validation failed',
                    'details' => $validator->errors(),
                    'code' => 422
                ]
            ], 422)
        );
    }
} 