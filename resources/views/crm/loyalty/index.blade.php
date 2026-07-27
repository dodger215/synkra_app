<x-layouts.app title="Loyalty Programs">
    <div class="flowexa-dashboard-container" style="padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--headings); margin: 0; font-weight: 800;">Loyalty & Rewards</h1>
                <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0;">Incentivize repeat business with points and rewards.</p>
            </div>
            <button onclick="document.getElementById('adjustModal').style.display='flex'" class="flowexa-btn" style="background: var(--primary); border: none; color: white; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-star"></i> Adjust Points
            </button>
        </div>

        @if(session('success'))
            <x-ui.alert type="success" title="Success" message="{{ session('success') }}" style="margin-bottom: 2rem;" />
        @endif

        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
            <div>
                <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem; margin-bottom: 2rem;">
                    <h3 style="color: var(--headings); margin: 0 0 1.5rem 0; font-size: 1.1rem; font-weight: 700;">Active Programs</h3>
                    @forelse($programs as $program)
                        <div style="padding: 1rem; background: var(--surface-secondary); border-radius: 16px; margin-bottom: 1rem;">
                            <div style="font-weight: 700; color: var(--headings);">{{ $program->name }}</div>
                            <div style="font-size: 0.85rem; color: var(--text-secondary);">{{ $program->points_per_currency }} points per ₵1 spent</div>
                        </div>
                    @empty
                        <p style="color: var(--text-secondary); text-align: center; padding: 1rem;">No active programs.</p>
                        <button onclick="document.getElementById('programModal').style.display='flex'" class="flowexa-btn" style="width: 100%; background: var(--surface-secondary); border: 1px dashed var(--primary); color: var(--primary); padding: 0.75rem; border-radius: 12px; font-weight: 600; cursor: pointer;">
                            + Create Program
                        </button>
                    @endforelse
                </div>
            </div>

            <div>
                <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); overflow: hidden;">
                    <h3 style="padding: 1.5rem; margin: 0; color: var(--headings); font-size: 1.1rem; font-weight: 700; border-bottom: 1px solid var(--border);">Recent Point Transactions</h3>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: var(--surface-secondary); border-bottom: 1px solid var(--border);">
                                <th style="text-align: left; padding: 1rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary);">Customer</th>
                                <th style="text-align: left; padding: 1rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary);">Points</th>
                                <th style="text-align: left; padding: 1rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary);">Type</th>
                                <th style="text-align: left; padding: 1rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary);">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $tx)
                                <tr style="border-bottom: 1px solid var(--border);">
                                    <td style="padding: 1rem 1.5rem; font-weight: 600;">{{ $tx->customer->first_name }} {{ $tx->customer->last_name }}</td>
                                    <td style="padding: 1rem 1.5rem; color: {{ $tx->points > 0 ? '#16a34a' : '#dc2626' }}; font-weight: 700;">
                                        {{ $tx->points > 0 ? '+' : '' }}{{ $tx->points }}
                                    </td>
                                    <td style="padding: 1rem 1.5rem;">
                                        <span class="flowexa-badge-pill" style="background: var(--surface-secondary); font-size: 0.7rem;">{{ strtoupper($tx->transaction_type) }}</span>
                                    </td>
                                    <td style="padding: 1rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary);">{{ $tx->description }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="padding: 3rem; text-align: center; color: var(--text-secondary);">No recent transactions.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Program Modal --}}
    <div id="programModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 1rem;">
        <div style="background: var(--surface); border-radius: 24px; max-width: 400px; width: 100%; border: 1px solid var(--border); box-shadow: 0 20px 50px rgba(0,0,0,0.2); overflow: hidden;">
            <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; color: var(--headings); font-weight: 800;">New Loyalty Program</h3>
                <button onclick="document.getElementById('programModal').style.display='none'" style="background: none; border: none; color: var(--text-secondary); cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('crm.loyalty.programs.store') }}" method="POST" style="padding: 1.5rem;">
                @csrf
                <div style="margin-bottom: 1.25rem;">
                    <x-ui.input name="name" label="Program Name" required placeholder="e.g. Bronze Rewards" />
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <x-ui.input type="number" name="points_per_currency" label="Points per ₵1 Spent" required value="1" />
                </div>
                <button type="submit" class="flowexa-btn" style="width: 100%; background: var(--primary); color: white; border: none; padding: 0.75rem; border-radius: 12px; font-weight: 700;">Create Program</button>
            </form>
        </div>
    </div>

    {{-- Adjust Modal --}}
    <div id="adjustModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 1rem;">
        <div style="background: var(--surface); border-radius: 24px; max-width: 450px; width: 100%; border: 1px solid var(--border); box-shadow: 0 20px 50px rgba(0,0,0,0.2); overflow: hidden;">
            <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; color: var(--headings); font-weight: 800;">Manual Point Adjustment</h3>
                <button onclick="document.getElementById('adjustModal').style.display='none'" style="background: none; border: none; color: var(--text-secondary); cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('crm.loyalty.adjust_points') }}" method="POST" style="padding: 1.5rem;">
                @csrf
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">Select Customer</label>
                    <select name="customer_id" required style="width: 100%; padding: 0.75rem; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary);">
                        @foreach(\App\Models\Customer::where('tenant_id', Auth::user()->tenant_id)->get() as $c)
                            <option value="{{ $c->id }}">{{ $c->first_name }} {{ $c->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom: 1rem;">
                    <x-ui.input type="number" name="points" label="Points (negative to subtract)" required />
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <x-ui.input name="reason" label="Reason for Adjustment" required />
                </div>
                <button type="submit" class="flowexa-btn" style="width: 100%; background: var(--primary); color: white; border: none; padding: 0.75rem; border-radius: 12px; font-weight: 700;">Apply Adjustment</button>
            </form>
        </div>
    </div>
</x-layouts.app>
