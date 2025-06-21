<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Trip;
use App\Enums\TripStatus;
use App\Services\DriverSelectionService;
use Illuminate\Support\Facades\Log;

class ProcessPendingTrips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trips:process-pending {--limit=10 : Number of trips to process}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process pending trips and assign drivers automatically';

    protected $driverSelectionService;

    public function __construct(DriverSelectionService $driverSelectionService)
    {
        parent::__construct();
        $this->driverSelectionService = $driverSelectionService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = $this->option('limit');
        
        $this->info("Processing up to {$limit} pending trips...");
        
        // Get pending trips ordered by creation time (oldest first)
        $pendingTrips = Trip::where('status', TripStatus::PENDING)
            ->whereNull('driver_id')
            ->orderBy('created_at', 'asc')
            ->limit($limit)
            ->get();
        
        if ($pendingTrips->isEmpty()) {
            $this->info('No pending trips found.');
            return 0;
        }
        
        $this->info("Found {$pendingTrips->count()} pending trips to process.");
        
        $assigned = 0;
        $failed = 0;
        
        foreach ($pendingTrips as $trip) {
            $this->line("Processing trip #{$trip->id}...");
            
            try {
                $driverAssigned = $this->driverSelectionService->autoAssignDriver($trip);
                
                if ($driverAssigned) {
                    $this->info("✓ Trip #{$trip->id} assigned to driver successfully.");
                    $assigned++;
                } else {
                    $this->warn("⚠ Trip #{$trip->id} - No suitable drivers available.");
                    $failed++;
                }
            } catch (\Exception $e) {
                $this->error("✗ Trip #{$trip->id} - Error: " . $e->getMessage());
                $failed++;
                Log::error("Failed to process trip {$trip->id}: " . $e->getMessage());
            }
        }
        
        $this->newLine();
        $this->info("Processing complete:");
        $this->info("- Successfully assigned: {$assigned}");
        $this->info("- Failed/No drivers: {$failed}");
        
        return 0;
    }
} 