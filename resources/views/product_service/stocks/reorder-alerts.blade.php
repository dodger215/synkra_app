<x-layouts.app title="Reorder Alerts">
    <x-ui.grid>
        <div class="flowexa-dashboard-container" style="padding:2rem;max-width:1200px;margin:0 auto;">
            <div style="margin-bottom:2rem;">
                <h1 style="color:var(--headings);margin:0 0 .5rem 0;">Reorder Alerts</h1>
                <p style="color:var(--text-secondary);margin:0;">Products below reorder thresholds. Critical items need immediate attention.</p>
            </div>

            @if(session('success'))
                <x-ui.alert type="success" title="Success" :message="session('success')" style="margin-bottom:2rem;" />
            @endif

            @php
                $summary = $reorderAlertSummary ?? ['critical' => 0, 'low' => 0, 'active_alerts' => 0, 'count' => 0];
                $criticalCount = $summary['critical'] ?? 0;
                $lowCount = $summary['low'] ?? 0;
                $activeAlertCount = $summary['active_alerts'] ?? 0;
            @endphp

            <div style="padding:1rem 1.25rem;background:rgba(15,23,42,.04);border:1px solid var(--border);border-radius:12px;margin-bottom:2rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
                <div>
                    <div style="font-size:.75rem;text-transform:uppercase;color:var(--text-secondary);font-weight:700;">Total Alerts</div>
                    <div style="font-size:1.75rem;font-weight:800;color:var(--headings);">{{ $summary['count'] ?? 0 }}</div>
                </div>
                <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                    @if($criticalCount > 0)
                        <x-ui.badge :text="(string) $criticalCount" variant="danger" pill="true" />
                    @endif
                    @if($lowCount > 0)
                        <x-ui.badge :text="(string) $lowCount" variant="warning" pill="true" />
                    @endif
                    @if($activeAlertCount > 0)
                        <x-ui.badge :text="(string) $activeAlertCount" variant="info" pill="true" />
                    @endif
                </div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:2rem;">
                <div style="padding:1rem 1.25rem;background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.25);border-radius:12px;">
                    <div style="font-size:.75rem;text-transform:uppercase;color:#dc2626;font-weight:700;">Critical</div>
                    <div style="font-size:1.75rem;font-weight:800;color:#dc2626;">{{ $criticalCount }}</div>
                </div>
                <div style="padding:1rem 1.25rem;background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.25);border-radius:12px;">
                    <div style="font-size:.75rem;text-transform:uppercase;color:#d97706;font-weight:700;">Low Stock</div>
                    <div style="font-size:1.75rem;font-weight:800;color:#d97706;">{{ $lowCount }}</div>
                </div>
                <div style="padding:1rem 1.25rem;background:rgba(59,130,246,.08);border:1px solid rgba(59,130,246,.25);border-radius:12px;">
                    <div style="font-size:.75rem;text-transform:uppercase;color:#2563eb;font-weight:700;">Active Alerts</div>
                    <div style="font-size:1.75rem;font-weight:800;color:#2563eb;">{{ $activeAlertCount }}</div>
                </div>
            </div>

            <div class="flowexa-card" style="background:var(--surface);border-radius:16px;border:1px solid var(--border);overflow:hidden;margin-bottom:2rem;">
                <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--border);"><h3 style="margin:0;font-size:1rem;color:var(--headings);">Low Stock Balances</h3></div>
                @if($lowStockBalances->isEmpty())
                    <div style="text-align:center;padding:3rem;color:var(--text-secondary);">All stock levels are healthy.</div>
                @else
                    @php
                        $headers = ['Product', 'Location', 'On Hand', 'Reorder Point', 'Status', 'Action'];
                        $rows = $lowStockBalances->map(function ($balance) {
                            $status = $balance->reorder_status;
                            $color = $status === 'critical' ? '#dc2626' : '#d97706';
                            $bg = $status === 'critical' ? 'rgba(239,68,68,.1)' : 'rgba(245,158,11,.1)';
                            $statusBadge = new \Illuminate\Support\HtmlString('<span style="display:inline-flex;padding:.2rem .6rem;border-radius:999px;font-size:.75rem;font-weight:700;background:' . $bg . ';color:' . $color . ';text-transform:capitalize;">' . e($status) . '</span>');

                            return [
                                $balance->product?->name ?? '—',
                                $balance->location?->name ?? '—',
                                $balance->quantity_on_hand,
                                $balance->product?->reorder_point ?? '—',
                                $statusBadge,
                                new \Illuminate\Support\HtmlString('<a href="' . e(route('product_service.stocks.receive.create')) . '" style="color:var(--primary);font-weight:600;text-decoration:none;font-size:.85rem;">Receive Stock</a>'),
                            ];
                        })->all();
                    @endphp
                    <x-ui.table :headers="$headers" :rows="$rows" />
                @endif
            </div>

            <div class="flowexa-card" style="background:var(--surface);border-radius:16px;border:1px solid var(--border);overflow:hidden;">
                <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--border);"><h3 style="margin:0;font-size:1rem;color:var(--headings);">Recorded Reorder Alerts</h3></div>
                @if($alerts->isEmpty())
                    <div style="text-align:center;padding:3rem;color:var(--text-secondary);">No active reorder alert records.</div>
                @else
                    @php
                        $alertHeaders = ['Product', 'Location', 'Current', 'Threshold', 'Suggested Qty', 'Actions'];
                        $alertRows = $alerts->map(function ($alert) {
                            return [
                                $alert->product?->name ?? '—',
                                $alert->location?->name ?? '—',
                                $alert->current_quantity,
                                $alert->threshold,
                                $alert->suggested_order_quantity ?? '—',
                                new \Illuminate\Support\HtmlString(
                                    '<form method="POST" action="' . e(route('product_service.stocks.reorder_alerts.resolve', $alert->id)) . '" style="display:inline;margin:0;">'
                                    . csrf_field()
                                    . '<button type="submit" style="background:transparent;border:1px solid var(--border);color:var(--primary);padding:.35rem .75rem;border-radius:6px;font-size:.8rem;font-weight:600;cursor:pointer;">Resolve</button>'
                                    . '</form>'
                                ),
                            ];
                        })->all();
                    @endphp
                    <x-ui.table :headers="$alertHeaders" :rows="$alertRows" />
                @endif
            </div>
        </div>
    </x-ui.grid>
</x-layouts.app>
