<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $vehicleId = $this->route('vehicle')->id ?? null;

        return [
            'model' => 'required|string|max:255',
            'plate_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('vehicles', 'plate_number')->ignore($vehicleId),
            ],
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'type' => 'required|in:sedan,suv,luxury,van',
            'color' => 'required|string|max:50',
            'driver_id' => 'nullable|exists:drivers,id',
            'is_available' => 'boolean',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'model.required' => 'Vehicle model is required.',
            'model.max' => 'Vehicle model cannot exceed 255 characters.',
            'plate_number.required' => 'Plate number is required.',
            'plate_number.max' => 'Plate number cannot exceed 20 characters.',
            'plate_number.unique' => 'This plate number is already registered.',
            'year.required' => 'Vehicle year is required.',
            'year.integer' => 'Vehicle year must be a number.',
            'year.min' => 'Vehicle year cannot be earlier than 1900.',
            'year.max' => 'Vehicle year cannot be in the future.',
            'type.required' => 'Vehicle type is required.',
            'type.in' => 'Vehicle type must be sedan, suv, luxury, or van.',
            'color.required' => 'Vehicle color is required.',
            'color.max' => 'Vehicle color cannot exceed 50 characters.',
            'driver_id.exists' => 'Selected driver does not exist.',
            'latitude.numeric' => 'Latitude must be a number.',
            'latitude.between' => 'Latitude must be between -90 and 90.',
            'longitude.numeric' => 'Longitude must be a number.',
            'longitude.between' => 'Longitude must be between -180 and 180.',
        ];
    }
} 