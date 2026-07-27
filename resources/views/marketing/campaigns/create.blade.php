<x-layouts.app title="Create Campaign">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('marketing.campaigns.index') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 8px; margin-bottom: 1rem;">
                <i class="fa-solid fa-arrow-left"></i> Back to Campaigns
            </a>
            <h1 style="color: var(--headings); margin: 0; font-weight: 800;">Launch New Campaign</h1>
            <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0;">Draft a new advertisement campaign across your connected platforms.</p>
        </div>

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 2rem;">
            <form action="{{ route('marketing.campaigns.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 1.5rem;">
                    <x-ui.input name="campaign_name" label="Campaign Name" placeholder="e.g. Summer Sale 2026" required value="{{ old('campaign_name') }}" />
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--headings); margin-bottom: 0.5rem;">Target Platform</label>
                        <select name="platform_id" required style="width: 100%; padding: 0.75rem; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary); outline: none;">
                            <option value="">-- Select Platform --</option>
                            @foreach($platforms as $platform)
                                <option value="{{ $platform->id }}" {{ old('platform_id') == $platform->id ? 'selected' : '' }}>{{ $platform->platform_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--headings); margin-bottom: 0.5rem;">Campaign Objective</label>
                        <select name="objective" required style="width: 100%; padding: 0.75rem; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary); outline: none;">
                            <option value="traffic">Traffic</option>
                            <option value="conversions">Conversions</option>
                            <option value="awareness">Brand Awareness</option>
                            <option value="engagement">Post Engagement</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                    <x-ui.input type="number" step="0.01" name="daily_budget" label="Daily Budget (₵)" placeholder="0.00" required value="{{ old('daily_budget') }}" />
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--headings); margin-bottom: 0.5rem;">Duration</label>
                        <div style="display: flex; align-items: center; gap: 10px; padding: 0.75rem; background: var(--surface-secondary); border-radius: 12px; border: 1px solid var(--border); color: var(--text-secondary); font-size: 0.9rem;">
                            <i class="fa-solid fa-calendar-days"></i>
                            <span>Continuous (until paused)</span>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid var(--border);">
                    <a href="{{ route('marketing.campaigns.index') }}" class="flowexa-btn" style="background: var(--surface-secondary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none;">Cancel</a>
                    <button type="submit" class="flowexa-btn" style="background: var(--primary); border: none; color: white; padding: 0.75rem 2rem; border-radius: 12px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.2);">
                        Create Campaign Draft
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
