<x-layouts.app title="Marketing Campaigns">
    <div class="flowexa-dashboard-container" style="padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--headings); margin: 0; font-weight: 800;">Ad Campaigns</h1>
                <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0;">Create and manage your cross-platform marketing campaigns.</p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('marketing.connections.index') }}" class="flowexa-btn" style="background: var(--surface); border: 1px solid var(--border); color: var(--text-primary); padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-link"></i> Platform Connections
                </a>
                <a href="{{ route('marketing.campaigns.create') }}" class="flowexa-btn" style="background: var(--primary); border: none; color: white; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-plus"></i> New Campaign
                </a>
            </div>
        </div>

        @if(session('success'))
            <x-ui.alert type="success" title="Success" message="{{ session('success') }}" style="margin-bottom: 2rem;" />
        @endif

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--surface-secondary); border-bottom: 1px solid var(--border);">
                        <th style="text-align: left; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase;">Campaign</th>
                        <th style="text-align: left; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase;">Platform</th>
                        <th style="text-align: left; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase;">Objective</th>
                        <th style="text-align: left; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase;">Budget</th>
                        <th style="text-align: right; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($campaigns as $campaign)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 1.25rem 1.5rem;">
                                <div style="font-weight: 700; color: var(--headings);">{{ $campaign->campaign_name }}</div>
                            </td>
                            <td style="padding: 1.25rem 1.5rem;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="font-weight: 600; color: var(--text-primary);">{{ $campaign->platform->platform_name }}</span>
                                </div>
                            </td>
                            <td style="padding: 1.25rem 1.5rem; text-transform: capitalize; color: var(--text-primary);">
                                {{ str_replace('_', ' ', $campaign->objective) }}
                            </td>
                            <td style="padding: 1.25rem 1.5rem; color: var(--text-primary);">
                                <span style="font-weight: 700;">₵{{ number_format($campaign->daily_budget, 2) }}</span> /day
                            </td>
                            <td style="padding: 1.25rem 1.5rem; text-align: right;">
                                <span class="flowexa-badge-pill" style="background: var(--surface-secondary); text-transform: uppercase;">{{ $campaign->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 4rem; text-align: center; color: var(--text-secondary);">
                                <i class="fa-solid fa-bullhorn" style="font-size: 2rem; margin-bottom: 1rem; display: block; opacity: 0.3;"></i>
                                No campaigns found. Create your first campaign to start reaching customers.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
