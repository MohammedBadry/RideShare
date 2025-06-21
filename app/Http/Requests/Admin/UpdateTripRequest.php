<?php

namespace App\Http\Requests\Admin;

use App\Enums\TripStatus;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTripRequest extends FormRequest
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
        return [
            'driver_id' => 'nullable|exists:drivers,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'status' => 'required|in:' . implode(',', array_column(TripStatus::cases(), 'value')),
            'pickup_location' => 'required|string|max:255',
            'dropoff_location' => 'required|string|max:255',
            'fare' => 'required|numeric|min:0',
            'pickup_latitude' => 'nullable|numeric|between:-90,90',
            'pickup_longitude' => 'nullable|numeric|between:-180,180',
            'dropoff_latitude' => 'nullable|numeric|between:-90,90',
            'dropoff_longitude' => 'nullable|numeric|between:-180,180',
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
            'driver_id.exists' => 'Selected driver does not exist.',
            'vehicle_id.exists' => 'Selected vehicle does not exist.',
            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status selected.',
            'pickup_location.required' => 'Pickup location is required.',
            'pickup_location.max' => 'Pickup location cannot exceed 255 characters.',
            'dropoff_location.required' => 'Dropoff location is required.',
            'dropoff_location.max' => 'Dropoff location cannot exceed 255 characters.',
            'fare.required' => 'Fare is required.',
            'fare.numeric' => 'Fare must be a number.',
            'fare.min' => 'Fare cannot be negative.',
            'pickup_latitude.numeric' => 'Pickup latitude must be a number.',
            'pickup_latitude.between' => 'Pickup latitude must be between -90 and 90.',
            'pickup_longitude.numeric' => 'Pickup longitude must be a number.',
            'pickup_longitude.between' => 'Pickup longitude must be between -180 and 180.',
            'dropoff_latitude.numeric' => 'Dropoff latitude must be a number.',
            'dropoff_latitude.between' => 'Dropoff latitude must be between -90 and 90.',
            'dropoff_longitude.numeric' => 'Dropoff longitude must be a number.',
            'dropoff_longitude.between' => 'Dropoff longitude must be between -180 and 180.',
        ];
    }
} 