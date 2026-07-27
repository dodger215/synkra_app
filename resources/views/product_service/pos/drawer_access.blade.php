<x-layouts.app title="Drawer Access Logs">
    <x-ui.grid>
        <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
                <div>
                    <h1 style="color: var(--headings); margin: 0 0 0.5rem 0; font-weight: 800;">Cash Drawer Access</h1>
                    <p style="color: var(--text-secondary); margin: 0;">Audit log of all manual and transaction-triggered drawer openings.</p>
                </div>
            </div>

            <div class="flowexa-card" style="background: var(--surface); border-radius: 20px; border: 1px solid var(--border); overflow: hidden; padding: 1.5rem;">
                @if($logs->isEmpty())
                    <div style="text-align: center; padding: 4rem 0; color: var(--text-secondary);">No drawer access logs found.</div>
                @else
                    @php
                        $headers = ['User', 'Device', 'Reason', 'Session', 'Timestamp'];
                        $rows = $logs->map(function($log) {
                            return [
                                $log->user->name,
                                $log->device ? $log->device->device_name : 'Unknown Device',
                                $log->reason ?? 'Manual Request',
                                $log->pos_session_id ? new \Illuminate\Support\HtmlString('<code style="color:var(--primary); font-weight:700;">#' . substr($log->pos_session_id, 0, 8) . '</code>') : 'N/A',
                                $log->created_at->format('M d, Y H:i:s'),
                            ];
                        })->toArray();
                    @endphp
                    <x-ui.table :headers="$headers" :rows="$rows" />

                    <div style="margin-top: 1.5rem;">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </x-ui.grid>
</x-layouts.app>
