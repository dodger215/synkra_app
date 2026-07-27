<x-layouts.app title="Create Segment">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 600px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('crm.segments.index') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 8px; margin-bottom: 1rem;">
                <i class="fa-solid fa-arrow-left"></i> Back to Segments
            </a>
            <h1 style="color: var(--headings); margin: 0; font-weight: 800;">Create Segment</h1>
        </div>

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 2rem;">
            <form action="{{ route('crm.segments.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 1.5rem;">
                    <x-ui.input name="name" label="Segment Name" required placeholder="e.g. High Value Customers" />
                </div>

                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">Description</label>
                    <textarea name="description" rows="4" style="width: 100%; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary); padding: 0.75rem; font-family: inherit; resize: vertical;" placeholder="Define the criteria for this group..."></textarea>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <a href="{{ route('crm.segments.index') }}" class="flowexa-btn" style="background: var(--surface-secondary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none;">Cancel</a>
                    <button type="submit" class="flowexa-btn" style="background: var(--primary); border: none; color: white; padding: 0.75rem 2rem; border-radius: 12px; font-weight: 700; cursor: pointer;">
                        Save Segment
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
