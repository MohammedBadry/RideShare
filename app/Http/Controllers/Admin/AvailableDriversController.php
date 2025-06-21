<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AvailableDriversRequest;
use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Cache;

class AvailableDriversController extends Controller
{
    public function index(AvailableDriversRequest $request)
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $radius = 5; // km

        $cacheKey = "available_drivers:{$lat}:{$lng}";
        $drivers = Cache::remember($cacheKey, 10, function() use ($lat, $lng, $radius) {
            return Vehicle::where('is_available', true)
                ->selectRaw("*, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance", [$lat, $lng, $lat])
                ->having('distance', '<=', $radius)
                ->orderBy('distance')
                ->with('driver.user')
                ->get();
        });

        return response()->json($drivers);
    }

    public function optimizedQuery(AvailableDriversRequest $request)
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $radius = 5; // km

        // Using raw SQL for better performance
        $drivers = \DB::select("
            SELECT
                v.*,
                d.license_number,
                u.name as driver_name,
                u.email as driver_email,
                (6371 * acos(cos(radians(?)) * cos(radians(v.latitude)) * cos(radians(v.longitude) - radians(?)) + sin(radians(?)) * sin(radians(v.latitude)))) AS distance
            FROM vehicles v
            JOIN drivers d ON v.driver_id = d.id
            JOIN users u ON d.user_id = u.id
            WHERE v.is_available = 1
            HAVING distance <= ?
            ORDER BY distance
        ", [$lat, $lng, $lat, $radius]);

        return response()->json($drivers);
    }
}
