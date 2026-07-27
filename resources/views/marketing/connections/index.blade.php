<x-layouts.app title="Marketing Connections">
    <div class="flowexa-dashboard-container" style="padding: 2rem;">
        <div style="margin-bottom: 2rem;">
            <h1 style="color: var(--headings); margin: 0; font-weight: 800;">Platform Connections</h1>
            <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0;">Connect your social media and search accounts to flowexa for unified marketing.</p>
        </div>

        @if(session('success'))
            <x-ui.alert type="success" title="Success" message="{{ session('success') }}" style="margin-bottom: 2rem;" />
        @endif

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem;">
            @foreach($platforms as $platform)
                @php
                    $connection = $connections->get($platform->id);
                    $icon = match(strtolower($platform->platform_name)) {
                        'meta', 'facebook', 'instagram' => 'fa-brands fa-facebook',
                        'google', 'google ads' => 'fa-brands fa-google',
                        'tiktok' => 'fa-brands fa-tiktok',
                        'twitter', 'x' => 'fa-brands fa-x-twitter',
                        default => 'fa-solid fa-share-nodes'
                    };
                    $color = match(strtolower($platform->platform_name)) {
                        'meta', 'facebook', 'instagram' => '#1877F2',
                        'google', 'google ads' => '#4285F4',
                        'tiktok' => '#000000',
                        'twitter', 'x' => '#000000',
                        default => 'var(--primary)'
                    };
                @endphp
                <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 2rem; display: flex; flex-direction: column; align-items: center; text-align: center;">
                    <div style="width: 80px; height: 80px; border-radius: 20px; background: {{ $color }}10; color: {{ $color }}; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin-bottom: 1.5rem;">
                        <i class="{{ $icon }}"></i>
                    </div>

                    <h3 style="margin: 0 0 0.5rem 0; color: var(--headings); font-weight: 800;">{{ $platform->platform_name }}</h3>

                    @if($connection)
                        <div style="background: rgba(34, 197, 94, 0.1); color: #22c55e; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; margin-bottom: 1rem;">CONNECTED</div>
                        <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 0.25rem;">{{ $connection->external_account_name }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 2rem;">ID: {{ $connection->external_account_id }}</div>

                        <form action="{{ route('marketing.connections.disconnect', $connection->id) }}" method="POST" style="width: 100%;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flowexa-btn" style="width: 100%; background: var(--surface-secondary); border: 1px solid var(--border); color: var(--danger); padding: 0.75rem; border-radius: 12px; font-weight: 700; cursor: pointer;">
                                Disconnect
                            </button>
                        </form>
                    @else
                        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 2rem; line-height: 1.5;">Connect your {{ $platform->platform_name }} account to sync products and run automated campaigns.</p>

                        <a href="{{ route('marketing.connections.connect', $platform->id) }}" class="flowexa-btn" style="width: 100%; background: var(--primary); color: white; border: none; padding: 0.75rem; border-radius: 12px; font-weight: 700; text-decoration: none;">
                            Connect Account
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.app>
