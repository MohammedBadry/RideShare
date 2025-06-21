<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\TripStatus;

class StoreTripRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'driver_id' => 'required|exists:drivers,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'origin_latitude' => 'required|numeric|between:-90,90',
            'origin_longitude' => 'required|numeric|between:-180,180',
            'destination_latitude' => 'required|numeric|between:-90,90',
            'destination_longitude' => 'required|numeric|between:-180,180',
            'fare' => 'required|numeric|min:0',
            'status' => 'sometimes|in:' . implode(',', TripStatus::values()),
            'pickup_time' => 'sometimes|date|after:now',
            'dropoff_time' => 'sometimes|date|after:pickup_time',
            'distance' => 'sometimes|numeric|min:0',
            'duration' => 'sometimes|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'User ID is required.',
            'user_id.exists' => 'The selected user does not exist.',
            'driver_id.required' => 'Driver ID is required.',
            'driver_id.exists' => 'The selected driver does not exist.',
            'vehicle_id.required' => 'Vehicle ID is required.',
            'vehicle_id.exists' => 'The selected vehicle does not exist.',
            'origin_latitude.required' => 'Origin latitude is required.',
            'origin_latitude.numeric' => 'Origin latitude must be a number.',
            'origin_latitude.between' => 'Origin latitude must be between -90 and 90.',
            'origin_longitude.required' => 'Origin longitude is required.',
            'origin_longitude.numeric' => 'Origin longitude must be a number.',
            'origin_longitude.between' => 'Origin longitude must be between -180 and 180.',
            'destination_latitude.required' => 'Destination latitude is required.',
            'destination_latitude.numeric' => 'Destination latitude must be a number.',
            'destination_latitude.between' => 'Destination latitude must be between -90 and 90.',
            'destination_longitude.required' => 'Destination longitude is required.',
            'destination_longitude.numeric' => 'Destination longitude must be a number.',
            'destination_longitude.between' => 'Destination longitude must be between -180 and 180.',
            'fare.required' => 'Fare is required.',
            'fare.numeric' => 'Fare must be a number.',
            'fare.min' => 'Fare must be at least 0.',
            'status.in' => 'Invalid trip status.',
            'pickup_time.date' => 'Pickup time must be a valid date.',
            'pickup_time.after' => 'Pickup time must be in the future.',
            'dropoff_time.date' => 'Dropoff time must be a valid date.',
            'dropoff_time.after' => 'Dropoff time must be after pickup time.',
            'distance.numeric' => 'Distance must be a number.',
            'distance.min' => 'Distance must be at least 0.',
            'duration.integer' => 'Duration must be an integer.',
            'duration.min' => 'Duration must be at least 0.',
        ];
    }
} 