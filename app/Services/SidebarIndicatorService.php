<?php

namespace App\Services;

use App\Models\ReorderAlert;
use App\Models\StockBalance;

class SidebarIndicatorService
{
    public function reorderAlerts(string $tenantId): array
    {
        $lowStockBalances = StockBalance::with('product')
            ->whereHas('product', fn ($query) => $query->where('tenant_id', $tenantId))
            ->get()
            ->filter(fn (StockBalance $balance) => in_array($balance->reorder_status, ['critical', 'low'], true));

        $critical = $lowStockBalances->where('reorder_status', 'critical')->count();
        $low = $lowStockBalances->where('reorder_status', 'low')->count();
        $activeAlerts = ReorderAlert::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->count();

        $count = $critical + $low + $activeAlerts;

        $variant = match (true) {
            $critical > 0 => 'danger',
            $low > 0 => 'warning',
            $activeAlerts > 0 => 'info',
            default => null,
        };

        return [
            'count' => $count,
            'variant' => $variant,
            'critical' => $critical,
            'low' => $low,
            'active_alerts' => $activeAlerts,
        ];
    }

    public function resolve(string $resolver, string $tenantId): ?array
    {
        $data = match ($resolver) {
            'reorder_alerts' => $this->reorderAlerts($tenantId),
            default => null,
        };

        if (! $data || $data['count'] <= 0 || ! $data['variant']) {
            return null;
        }

        return $data;
    }
}
