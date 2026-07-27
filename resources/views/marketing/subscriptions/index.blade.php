<x-layouts.app title="Marketing Subscriptions">
    <div class="flowexa-dashboard-container" style="padding: 2rem;">
        <div style="margin-bottom: 2rem; text-align: center;">
            <h1 style="color: var(--headings); margin: 0; font-weight: 800;">Marketing Plans</h1>
            <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0;">Unlock powerful cross-platform marketing tools for your shop.</p>
        </div>

        @if(session('success'))
            <x-ui.alert type="success" title="Success" message="{{ session('success') }}" style="margin-bottom: 2rem;" />
        @endif
        @if(session('error'))
            <x-ui.alert type="danger" title="Error" message="{{ session('error') }}" style="margin-bottom: 2rem;" />
        @endif

        @if($subscription)
            <div class="flowexa-card" style="background: rgba(59, 130, 246, 0.05); border: 1px solid var(--primary); padding: 1.5rem; border-radius: 20px; margin-bottom: 3rem; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 style="margin: 0; color: var(--primary);">Active Plan: {{ ucfirst($subscription->plan_name) }}</h3>
                    <p style="margin: 0.25rem 0 0 0; color: var(--text-secondary);">Renews on {{ $subscription->ends_at->format('M d, Y') }} (₵{{ number_format($subscription->monthly_price, 2) }}/mo)</p>
                </div>
                <div style="background: var(--primary); color: white; padding: 0.5rem 1rem; border-radius: 12px; font-weight: 700;">PRO ACCOUNT</div>
            </div>
        @endif

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            @foreach($plans as $key => $plan)
                @php
                    $isCurrent = $subscription && $subscription->plan_name === $key;
                @endphp
                <div class="flowexa-card" style="background: var(--surface); border-radius: 30px; border: 1px solid {{ $isCurrent ? 'var(--primary)' : 'var(--border)' }}; padding: 2.5rem; display: flex; flex-direction: column; position: relative;">
                    @if($key === 'professional')
                        <div style="position: absolute; top: -15px; left: 50%; transform: translateX(-50%); background: var(--primary); color: white; padding: 0.5rem 1.5rem; border-radius: 20px; font-size: 0.8rem; font-weight: 800;">MOST POPULAR</div>
                    @endif

                    <h3 style="margin: 0; color: var(--headings); font-weight: 800; font-size: 1.5rem;">{{ $plan['name'] }}</h3>
                    <div style="margin: 1.5rem 0; display: flex; align-items: baseline; gap: 4px;">
                        <span style="font-size: 2.5rem; font-weight: 900; color: var(--headings);">₵{{ number_format($plan['price']) }}</span>
                        <span style="color: var(--text-secondary); font-weight: 600;">/month</span>
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <div style="font-weight: 800; font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1rem;">Platforms Included</div>
                        <div style="display: flex; gap: 10px; margin-bottom: 2rem;">
                            @foreach($plan['platforms'] as $plt)
                                <div style="width: 36px; height: 36px; border-radius: 10px; background: var(--surface-secondary); display: flex; align-items: center; justify-content: center; color: var(--headings);">
                                    <i class="fa-brands fa-{{ strtolower($plt) === 'meta' ? 'facebook' : strtolower($plt) }}"></i>
                                </div>
                            @endforeach
                        </div>

                        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem;">
                            @foreach($plan['features'] as $feature)
                                <li style="display: flex; align-items: center; gap: 10px; font-size: 0.95rem; color: var(--text-primary);">
                                    <i class="fa-solid fa-circle-check" style="color: #10b981;"></i>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <form action="{{ route('marketing.subscriptions.subscribe') }}" method="POST" style="margin-top: auto;">
                        @csrf
                        <input type="hidden" name="plan" value="{{ $key }}">
                        <button type="submit"
                                {{ $isCurrent ? 'disabled' : '' }}
                                class="flowexa-btn"
                                style="width: 100%; background: {{ $isCurrent ? 'var(--surface-secondary)' : 'var(--primary)' }}; color: {{ $isCurrent ? 'var(--text-secondary)' : 'white' }}; border: none; padding: 1rem; border-radius: 16px; font-weight: 800; font-size: 1.1rem; cursor: pointer; transition: transform 0.2s;">
                            {{ $isCurrent ? 'Current Plan' : 'Select Plan' }}
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.app>
