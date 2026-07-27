<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;

class ModuleFeatureService
{
    /**
     * Get features that should be enabled based on active sub-modules.
     */
    public function getEnabledFeatures(?Tenant $tenant = null): array
    {
        $tenant = $tenant ?? Auth::user()?->tenant;
        if (!$tenant) return [];

        $features = [];
        $activeServices = $tenant->services()->where('is_active', true)->get(['service_name', 'sub_category'])->keyBy('service_name');

        foreach ($activeServices as $name => $service) {
            $sub = $service->sub_category;

            if ($name === 'pos') {
                if ($sub === 'Restaurant POS') {
                    $features[] = 'pos.tables';
                    $features[] = 'pos.kitchen_display';
                    $features[] = 'pos.reservations';
                } elseif ($sub === 'Service POS') {
                    $features[] = 'pos.appointments';
                    $features[] = 'pos.staff_assignment';
                }
            }

            if ($name === 'inventory') {
                if ($sub === 'Multi-Warehouse') {
                    $features[] = 'inventory.advanced_locations';
                    $features[] = 'inventory.transfers';
                } elseif ($sub === 'Manufacturing') {
                    $features[] = 'inventory.bom';
                    $features[] = 'inventory.production_orders';
                }
            }

            if ($name === 'crm') {
                if ($sub === 'Advanced CRM & Loyalty') {
                    $features[] = 'crm.loyalty_points';
                    $features[] = 'crm.tiers';
                    $features[] = 'crm.rewards';
                }
            }

            if ($name === 'marketing') {
                if ($sub === 'Social Ads Integration') {
                    $features[] = 'marketing.meta_ads';
                    $features[] = 'marketing.google_ads';
                }
            }

            if ($name === 'supply_chain') {
                if ($sub === 'Advanced Supply Chain') {
                    $features[] = 'supply_chain.forecasting';
                    $features[] = 'supply_chain.shipment_tracking';
                }
            }

            if ($name === 'reporting') {
                if ($sub === 'Custom BI Dashboards') {
                    $features[] = 'reporting.custom_dashboards';
                    $features[] = 'reporting.advanced_analytics';
                }
            }
        }

        return $features;
    }

    /**
     * Check if a specific feature is enabled for the current tenant.
     */
    public function isFeatureEnabled(string $feature): bool
    {
        return in_array($feature, $this->getEnabledFeatures());
    }
}
