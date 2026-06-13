<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\SystemNotification;
use App\Models\Product;
use App\Models\DemandForecast;

class GenerateDemandForecasts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tenantId;

    /**
     * Create a new job instance.
     */
    public function __construct($tenantId)
    {
        $this->tenantId = $tenantId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Simulate complex demand forecasting calculation
        $products = Product::where('tenant_id', $this->tenantId)->get();

        foreach ($products as $product) {
            // Simplified forecast calculation (this would be ML/stats in a real app)
            $historicalSalesAvg = rand(10, 50); // placeholder average
            $forecastQuantity = $historicalSalesAvg * 1.1; // 10% expected growth
            
            DemandForecast::updateOrCreate(
                [
                    'tenant_id' => $this->tenantId,
                    'product_id' => $product->id,
                    'forecast_date' => now()->addMonth()->startOfMonth(),
                ],
                [
                    'forecast_quantity' => $forecastQuantity,
                    'confidence_lower' => $forecastQuantity * 0.8,
                    'confidence_upper' => $forecastQuantity * 1.2,
                    'model_version' => 'v1.0.0',
                    'factors' => ['seasonal_trend' => 1.05, 'market_growth' => 1.05],
                ]
            );
        }

        // Send a system notification when complete
        SystemNotification::create([
            'tenant_id' => $this->tenantId,
            'notification_type' => 'forecast_complete',
            'severity' => 'info',
            'title' => 'Demand Forecast Completed',
            'message' => 'The demand forecast generation job has finished successfully for ' . $products->count() . ' products.',
            'is_read' => false,
        ]);
    }
}
