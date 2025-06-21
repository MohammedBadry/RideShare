<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TripStatus;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'driver_id',
        'vehicle_id',
        'pickup_location',
        'dropoff_location',
        'origin_latitude',
        'origin_longitude',
        'destination_latitude',
        'destination_longitude',
        'start_time',
        'end_time',
        'fare',
        'status'
    ];

    protected $casts = [
        'status' => TripStatus::class,
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'fare' => 'decimal:2',
        'origin_latitude' => 'decimal:7',
        'origin_longitude' => 'decimal:7',
        'destination_latitude' => 'decimal:7',
        'destination_longitude' => 'decimal:7',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
