<?php

namespace App\Http\Requests\Admin;

use App\Enums\TripStatus;
use Illuminate\Foundation\Http\FormRequest;

class StoreTripRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'driver_id' => 'required|exists:drivers,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'pickup_location' => 'required|string|max:255',
            'dropoff_location' => 'required|string|max:255',
            'fare' => 'required|numeric|min:0',
            'status' => 'required|in:' . implode(',', array_column(TripStatus::cases(), 'value')),
            'pickup_latitude' => 'nullable|numeric|between:-90,90',
            'pickup_longitude' => 'nullable|numeric|between:-180,180',
            'dropoff_latitude' => 'nullable|numeric|between:-90,90',
            'dropoff_longitude' => 'nullable|numeric|between:-180,180',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
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
            'user_id.required' => 'User is required.',
            'user_id.exists' => 'Selected user does not exist.',
            'driver_id.required' => 'Driver is required.',
            'driver_id.exists' => 'Selected driver does not exist.',
            'vehicle_id.required' => 'Vehicle is required.',
            'vehicle_id.exists' => 'Selected vehicle does not exist.',
            'pickup_location.required' => 'Pickup location is required.',
            'pickup_location.max' => 'Pickup location cannot exceed 255 characters.',
            'dropoff_location.required' => 'Dropoff location is required.',
            'dropoff_location.max' => 'Dropoff location cannot exceed 255 characters.',
            'fare.required' => 'Fare is required.',
            'fare.numeric' => 'Fare must be a number.',
            'fare.min' => 'Fare cannot be negative.',
            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status selected.',
            'pickup_latitude.numeric' => 'Pickup latitude must be a number.',
            'pickup_latitude.between' => 'Pickup latitude must be between -90 and 90.',
            'pickup_longitude.numeric' => 'Pickup longitude must be a number.',
            'pickup_longitude.between' => 'Pickup longitude must be between -180 and 180.',
            'dropoff_latitude.numeric' => 'Dropoff latitude must be a number.',
            'dropoff_latitude.between' => 'Dropoff latitude must be between -90 and 90.',
            'dropoff_longitude.numeric' => 'Dropoff longitude must be a number.',
            'dropoff_longitude.between' => 'Dropoff longitude must be between -180 and 180.',
            'start_time.date' => 'Start time must be a valid date.',
            'end_time.date' => 'End time must be a valid date.',
            'end_time.after' => 'End time must be after start time.',
        ];
    }
} 