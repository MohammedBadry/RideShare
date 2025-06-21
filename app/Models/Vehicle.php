<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\VehicleType;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'model',
        'plate_number',
        'year',
        'type',
        'color',
        'driver_id',
        'is_available',
        'latitude',
        'longitude'
    ];

    protected $casts = [
        'type' => VehicleType::class,
        'is_available' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'year' => 'integer',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
