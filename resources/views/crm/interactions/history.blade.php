<x-layouts.app title="Communication History">
    <div class="flowexa-dashboard-container" style="padding: 2rem;">
        <div style="margin-bottom: 2rem;">
            <h1 style="color: var(--headings); margin: 0; font-weight: 800;">Communication History</h1>
            <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0;">Review all automated and manual messages sent to customers.</p>
        </div>

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--surface-secondary); border-bottom: 1px solid var(--border);">
                        <th style="text-align: left; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase;">Sent Date</th>
                        <th style="text-align: left; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase;">Customer</th>
                        <th style="text-align: left; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase;">Channel</th>
                        <th style="text-align: left; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase;">Subject / Preview</th>
                        <th style="text-align: right; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $item)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 1.25rem 1.5rem; color: var(--text-primary);">
                                {{ $item->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td style="padding: 1.25rem 1.5rem;">
                                <div style="font-weight: 700; color: var(--headings);">{{ $item->customer->first_name }} {{ $item->customer->last_name }}</div>
                            </td>
                            <td style="padding: 1.25rem 1.5rem;">
                                @if($item->interaction_type === 'email')
                                    <span style="color: #3b82f6;"><i class="fa-solid fa-envelope"></i> Email</span>
                                @elseif($item->interaction_type === 'sms')
                                    <span style="color: #10b981;"><i class="fa-solid fa-comment-sms"></i> SMS</span>
                                @else
                                    <span style="color: var(--text-secondary);"><i class="fa-solid fa-paper-plane"></i> Message</span>
                                @endif
                            </td>
                            <td style="padding: 1.25rem 1.5rem;">
                                <div style="font-weight: 600; color: var(--headings);">{{ $item->subject ?? 'No Subject' }}</div>
                                <div style="font-size: 0.85rem; color: var(--text-secondary);">{{ Str::limit($item->content, 60) }}</div>
                            </td>
                            <td style="padding: 1.25rem 1.5rem; text-align: right;">
                                <span style="background: rgba(34, 197, 94, 0.1); color: #22c55e; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">DELIVERED</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 4rem; text-align: center; color: var(--text-secondary);">
                                No communication history found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="padding: 1.5rem;">
                {{ $history->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
