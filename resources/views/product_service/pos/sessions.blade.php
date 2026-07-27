<x-layouts.app title="POS Sessions">
    <x-ui.grid>
        <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
                <div>
                    <h1 style="color: var(--headings); margin: 0 0 0.5rem 0;">POS Sessions</h1>
                    <p style="color: var(--text-secondary); margin: 0;">History of cashier sessions, cash movements, and closing variances.</p>
                </div>
                <a href="{{ route('product_service.pos.index') }}" class="flowexa-btn flowexa-btn-primary" style="background: var(--primary); border: none; color: white; padding: 0.6rem 1.25rem; border-radius: 8px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-cash-register"></i> Open POS
                </a>
            </div>

            @if(session('success'))
                <x-ui.alert type="success" title="Success" message="{{ session('success') }}" style="margin-bottom: 1.5rem;" />
            @endif

            <div class="flowexa-card" style="background: var(--surface); border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid var(--border); overflow: hidden; padding: 1.5rem;">
                @if($sessions->isEmpty())
                    <div style="text-align: center; padding: 4rem 2rem;">
                        <div style="width: 64px; height: 64px; background: var(--surface-secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto; font-size: 1.5rem; color: var(--text-secondary);">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <h3 style="margin: 0 0 0.5rem 0; color: var(--text-primary);">No Sessions Found</h3>
                        <p style="color: var(--text-secondary); margin: 0 0 1.5rem 0; font-size: 0.95rem;">No cashier sessions have been recorded yet.</p>
                    </div>
                @else
                    @php
                        $headers = ['Session ID', 'Cashier', 'Device', 'Started At', 'Ended At', 'Total Sales', 'Status', 'Variance'];
                        $rows = $sessions->map(function ($session) {
                            $statusColor = $session->status === 'open' ? '#16a34a' : '#64748b';
                            $statusHtml = new \Illuminate\Support\HtmlString(
                                '<span style="display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:99px;font-size:0.7rem;font-weight:700;text-transform:uppercase;background:' . $statusColor . '15;color:' . $statusColor . ';">'
                                . '<i class="fa-solid ' . ($session->status === 'open' ? 'fa-circle-dot' : 'fa-lock') . '"></i> '
                                . e(ucfirst($session->status))
                                . '</span>'
                            );

                            $totalSales = $session->cash_sales + $session->card_sales;
                            $varianceColor = $session->variance < 0 ? '#ef4444' : ($session->variance > 0 ? '#16a34a' : 'var(--text-secondary)');
                            $varianceHtml = new \Illuminate\Support\HtmlString(
                                '<span style="font-weight:700; color:' . $varianceColor . ';">'
                                . ($session->variance > 0 ? '+' : '') . number_format($session->variance, 2)
                                . '</span>'
                            );

                            return [
                                new \Illuminate\Support\HtmlString('<code style="color:var(--primary); font-weight:700;">#' . e(substr($session->id, 0, 8)) . '</code>'),
                                $session->cashier->name,
                                $session->device ? $session->device->device_name : new \Illuminate\Support\HtmlString('<span style="color:var(--text-secondary);font-style:italic;">Manual</span>'),
                                $session->started_at->format('M d, H:i'),
                                $session->ended_at ? $session->ended_at->format('M d, H:i') : '-',
                                new \Illuminate\Support\HtmlString('<strong style="color:var(--headings);">$' . number_format($totalSales, 2) . '</strong>'),
                                $statusHtml,
                                $varianceHtml,
                            ];
                        })->all();
                    @endphp
                    <x-ui.table :headers="$headers" :rows="$rows" />

                    <div style="margin-top: 1.5rem;">
                        {{ $sessions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </x-ui.grid>
</x-layouts.app>
