<x-layouts.app title="Workspace Settings">
    <x-slot:head>
        <meta name="description" content="Update your company workspace settings.">
    </x-slot:head>

    <div class="synkra-dashboard-container" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <h1 style="color: var(--headings); margin: 0 0 0.5rem 0;">Workspace Settings</h1>
            <p style="color: var(--text-secondary); margin: 0;">Manage your company details and global workspace configuration.</p>
        </div>

        @if(session('status'))
            <x-ui.alert type="success" title="Success" message="{{ session('status') }}" style="margin-bottom: 2rem;" />
        @endif
        
        @if($errors->any())
            <x-ui.alert type="danger" title="Error" message="Please fix the errors below." style="margin-bottom: 2rem;" />
        @endif

        <div class="synkra-card" style="padding: 2rem; background: var(--surface); border-radius: 20px; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.06); border: 1px solid var(--border);">
            <form action="{{ route('settings.workspace.update') }}" method="POST">
                @csrf
                
                <h3 style="color: var(--headings); margin: 0 0 1.5rem 0; border-bottom: 1px solid var(--border); padding-bottom: 1rem;">Company Information</h3>
                
                <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                    <x-ui.input name="name" label="Workspace Name" placeholder="e.g. Acme Corp" value="{{ old('name', $tenant->name) }}" required />
                    
                    <div>
                        <x-ui.input name="subdomain" label="Custom Subdomain (Optional)" placeholder="acme" value="{{ old('subdomain', $tenant->subdomain) }}" />
                        <p style="margin: 0.5rem 0 0 0; font-size: 0.8rem; color: var(--text-secondary);">If set, you can access your workspace at <strong style="color: var(--primary);">subdomain.synkra.test</strong></p>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid var(--border);">
                    <button type="submit" class="synkra-btn synkra-btn-primary" style="background: var(--primary); border: none; color: white; cursor: pointer; padding: 0.75rem 2rem; border-radius: 10px; font-weight: 600;">Save Workspace</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
