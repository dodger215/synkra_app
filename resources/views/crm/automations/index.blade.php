<x-layouts.app title="Automation Triggers">
    <div class="flowexa-dashboard-container" style="padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--headings); margin: 0; font-weight: 800;">Automation Triggers</h1>
                <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0;">Set up rules to automatically send messages based on customer events.</p>
            </div>
            <a href="{{ route('crm.automations.create') }}" class="flowexa-btn" style="background: var(--primary); border: none; color: white; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-bolt"></i> Create Trigger
            </a>
        </div>

        @if(session('success'))
            <x-ui.alert type="success" title="Success" message="{{ session('success') }}" style="margin-bottom: 2rem;" />
        @endif

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
            @forelse($automations as $auto)
                <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                        <span style="background: rgba(59, 130, 246, 0.1); color: var(--primary); padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase;">
                            {{ str_replace('_', ' ', $auto->event_type) }}
                        </span>
                        <x-ui.switch name="active_{{ $auto->id }}" :checked="$auto->is_active" />
                    </div>
                    <h3 style="margin: 0 0 0.5rem 0; color: var(--headings); font-weight: 700;">{{ $auto->name }}</h3>
                    <div style="display: flex; align-items: center; gap: 8px; color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 1.5rem;">
                        <i class="fa-solid fa-arrow-right"></i>
                        <span>Action: {{ str_replace('_', ' ', $auto->action_type) }}</span>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: auto; padding-top: 1rem; border-top: 1px solid var(--border);">
                        <form action="{{ route('crm.automations.destroy', $auto->id) }}" method="POST" style="width: 100%;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="width: 100%; background: none; border: 1px solid var(--border); color: var(--danger); padding: 0.5rem; border-radius: 10px; font-size: 0.85rem; font-weight: 600; cursor: pointer;">
                                <i class="fa-solid fa-trash"></i> Remove
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 5rem 0; background: var(--surface); border-radius: 24px; border: 1px dashed var(--border);">
                    <i class="fa-solid fa-robot" style="font-size: 3rem; color: var(--text-secondary); opacity: 0.2; margin-bottom: 1rem; display: block;"></i>
                    <h3 style="color: var(--text-secondary);">No active automations</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Automate your customer engagement with event-based triggers.</p>
                    <a href="{{ route('crm.automations.create') }}" class="flowexa-btn" style="background: var(--primary); color: white; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none;">New Automation</a>
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.app>
