<x-layouts.app title="Segments">
    <div class="flowexa-dashboard-container" style="padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--headings); margin: 0; font-weight: 800;">Customer Segments</h1>
                <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0;">Group customers based on behaviors and attributes for targeted marketing.</p>
            </div>
            <a href="{{ route('crm.segments.create') }}" class="flowexa-btn" style="background: var(--primary); border: none; color: white; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-layer-group"></i> Create Segment
            </a>
        </div>

        @if(session('success'))
            <x-ui.alert type="success" title="Success" message="{{ session('success') }}" style="margin-bottom: 2rem;" />
        @endif

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem;">
            @forelse($segments as $segment)
                <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem; position: relative;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(59, 130, 246, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                            <i class="fa-solid fa-users-viewfinder"></i>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('crm.segments.edit', $segment->id) }}" style="color: var(--text-secondary);"><i class="fa-solid fa-pen"></i></a>
                            <form action="{{ route('crm.segments.destroy', $segment->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this segment?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: var(--danger); cursor: pointer; padding: 0;"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                    <h3 style="margin: 0 0 0.5rem 0; color: var(--headings); font-weight: 700;">{{ $segment->name }}</h3>
                    <p style="color: var(--text-secondary); font-size: 0.85rem; line-height: 1.5; margin-bottom: 1.5rem;">{{ $segment->description ?? 'No description provided.' }}</p>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 1rem; border-top: 1px solid var(--border);">
                        <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-secondary);">Member Count</span>
                        <span style="background: var(--surface-secondary); padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 700; color: var(--headings);">0</span>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 5rem 0; background: var(--surface); border-radius: 24px; border: 1px dashed var(--border);">
                    <i class="fa-solid fa-layer-group" style="font-size: 3rem; color: var(--text-secondary); opacity: 0.2; margin-bottom: 1rem; display: block;"></i>
                    <h3 style="color: var(--text-secondary);">No segments created yet</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Group your customers to send targeted offers.</p>
                    <a href="{{ route('crm.segments.create') }}" class="flowexa-btn" style="background: var(--primary); color: white; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none;">Create First Segment</a>
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.app>
