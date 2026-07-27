<x-layouts.app title="Interactions">
    <div class="flowexa-dashboard-container" style="padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--headings); margin: 0; font-weight: 800;">Customer Interactions</h1>
                <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0;">Log and track all touchpoints with your customers.</p>
            </div>
            <a href="{{ route('crm.interactions.create') }}" class="flowexa-btn" style="background: var(--primary); border: none; color: white; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-plus"></i> Log Interaction
            </a>
        </div>

        @if(session('success'))
            <x-ui.alert type="success" title="Success" message="{{ session('success') }}" style="margin-bottom: 2rem;" />
        @endif

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--surface-secondary); border-bottom: 1px solid var(--border);">
                        <th style="text-align: left; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase;">Date</th>
                        <th style="text-align: left; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase;">Customer</th>
                        <th style="text-align: left; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase;">Type</th>
                        <th style="text-align: left; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase;">Notes</th>
                        <th style="text-align: right; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($interactions as $interaction)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 1.25rem 1.5rem; color: var(--text-primary);">
                                {{ \Carbon\Carbon::parse($interaction->interaction_date)->format('M d, Y') }}
                            </td>
                            <td style="padding: 1.25rem 1.5rem; font-weight: 700; color: var(--headings);">
                                {{ $interaction->customer->first_name }} {{ $interaction->customer->last_name }}
                            </td>
                            <td style="padding: 1.25rem 1.5rem;">
                                <span class="flowexa-badge-pill" style="background: var(--surface-secondary); text-transform: capitalize;">{{ $interaction->interaction_type }}</span>
                            </td>
                            <td style="padding: 1.25rem 1.5rem; color: var(--text-secondary); font-size: 0.9rem;">
                                {{ Str::limit($interaction->notes, 50) }}
                            </td>
                            <td style="padding: 1.25rem 1.5rem; text-align: right;">
                                <form action="{{ route('crm.interactions.destroy', $interaction->id) }}" method="POST" onsubmit="return confirm('Delete this log?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: var(--danger); cursor: pointer;"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 4rem; text-align: center; color: var(--text-secondary);">
                                No interactions logged yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="padding: 1.5rem;">
                {{ $interactions->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
