<div wire:poll.10s="loadData" class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1400px; margin: 0 auto;">

    {{-- Header & Quick Actions --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2.5rem;">
        <div>
            <h1 style="color: var(--headings); margin: 0 0 0.5rem 0; font-size: 1.75rem; font-weight: 800;">Command Center</h1>
            <p style="color: var(--text-secondary); margin: 0; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-bolt" style="color: var(--success);"></i>
                Real-time metrics active for {{ auth()->user()->tenant->name ?? 'Workspace' }}
            </p>
        </div>
        <div style="display: flex; gap: 1rem;">
            @if(in_array('pos', $activeServices))
            <a href="{{ route('product_service.pos.index') }}" class="flowexa-btn" style="background: var(--primary); border: none; color: white; padding: 0.7rem 1.4rem; border-radius: 12px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(249,115,22,0.2);">
                <i class="fa-solid fa-cash-register"></i> Launch POS
            </a>
            @endif
            @if(in_array('crm', $activeServices))
            <a href="{{ route('crm.customers.create') }}" class="flowexa-btn" style="background: var(--surface); border: 1px solid var(--border); color: var(--text-primary); padding: 0.7rem 1.4rem; border-radius: 12px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-user-plus"></i> Add Customer
            </a>
            @endif
        </div>
    </div>

    {{-- Top Metrics Grid --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        @if(in_array('pos', $activeServices) || in_array('ecommerce', $activeServices))
        <div class="flowexa-card metric-card" style="background: var(--surface); padding: 1.5rem; border-radius: 20px; border: 1px solid var(--border);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <div style="width: 42px; height: 42px; background: rgba(249,115,22,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--primary);">
                    <i class="fa-solid fa-chart-line" style="font-size: 1.1rem;"></i>
                </div>
            </div>
            <div style="color: var(--text-secondary); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Daily Revenue</div>
            <div style="color: var(--headings); font-size: 1.75rem; font-weight: 800;">GH₵ {{ number_format($metrics['today_revenue'] ?? 0, 2) }}</div>
        </div>
        @endif

        @if(in_array('crm', $activeServices))
        <div class="flowexa-card metric-card" style="background: var(--surface); padding: 1.5rem; border-radius: 20px; border: 1px solid var(--border);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <div style="width: 42px; height: 42px; background: rgba(59,130,246,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #3b82f6;">
                    <i class="fa-solid fa-users" style="font-size: 1.1rem;"></i>
                </div>
            </div>
            <div style="color: var(--text-secondary); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Total Customers</div>
            <div style="color: var(--headings); font-size: 1.75rem; font-weight: 800;">{{ number_format($crmStats['total_customers'] ?? 0) }}</div>
        </div>
        @endif

        @if(in_array('supply_chain', $activeServices))
        <div class="flowexa-card metric-card" style="background: var(--surface); padding: 1.5rem; border-radius: 20px; border: 1px solid var(--border);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <div style="width: 42px; height: 42px; background: rgba(16,185,129,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #10b981;">
                    <i class="fa-solid fa-truck-fast" style="font-size: 1.1rem;"></i>
                </div>
                @if(($supplyChainStats['pending_requests'] ?? 0) > 0)
                    <span style="background: var(--danger); color: white; font-size: 0.65rem; padding: 2px 6px; border-radius: 10px; font-weight: 900;">{{ $supplyChainStats['pending_requests'] }} REQ</span>
                @endif
            </div>
            <div style="color: var(--text-secondary); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Partners</div>
            <div style="color: var(--headings); font-size: 1.75rem; font-weight: 800;">{{ number_format($supplyChainStats['active_suppliers'] ?? 0) }}</div>
        </div>
        @endif

        @if(in_array('inventory', $activeServices))
        <div class="flowexa-card metric-card" style="background: var(--surface); padding: 1.5rem; border-radius: 20px; border: 1px solid var(--border);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <div style="width: 42px; height: 42px; background: rgba(99,102,241,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #6366f1;">
                    <i class="fa-solid fa-boxes-stacked" style="font-size: 1.1rem;"></i>
                </div>
            </div>
            <div style="color: var(--text-secondary); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Active SKUs</div>
            <div style="color: var(--headings); font-size: 1.75rem; font-weight: 800;">{{ number_format($metrics['products_count'] ?? 0) }}</div>
        </div>
        @endif

        <div class="flowexa-card metric-card" style="background: var(--surface); padding: 1.5rem; border-radius: 20px; border: 1px solid var(--border);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <div style="width: 42px; height: 42px; background: rgba(245,158,11,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--warning);">
                    <i class="fa-solid fa-users" style="font-size: 1.1rem;"></i>
                </div>
            </div>
            <div style="color: var(--text-secondary); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Team Capacity</div>
            <div style="color: var(--headings); font-size: 1.75rem; font-weight: 800;">{{ number_format($metrics['users_count'] ?? 0) }}</div>
        </div>
    </div>

    {{-- Main Insights Area --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem;">

        {{-- Left Column: Performance & Activity (Depends on Sales Services) --}}
        @if(in_array('pos', $activeServices) || in_array('ecommerce', $activeServices))
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 2rem; min-height: 400px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h3 style="color: var(--headings); margin: 0; font-size: 1.1rem; font-weight: 700;">Performance Pulse (7D)</h3>
                    <div style="font-size: 0.8rem; color: var(--text-secondary); font-weight: 600;">
                        Goal: <span style="color: var(--headings);">GH₵ 5,000</span>
                    </div>
                </div>

                {{-- Simulated CSS Chart --}}
                <div style="display: flex; align-items: flex-end; justify-content: space-between; height: 250px; padding: 0 1rem; border-bottom: 1px solid var(--divider); position: relative;">
                    @php
                        $maxRev = collect($revenueData)->max('amount') ?: 100;
                    @endphp
                    @foreach($revenueData as $data)
                        @php $height = ($data['amount'] / $maxRev) * 100; @endphp
                        <div style="flex: 1; max-width: 40px; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                            <div title="GH₵ {{ number_format($data['amount'], 2) }}" style="width: 100%; height: {{ max($height, 5) }}%; background: linear-gradient(to top, var(--primary), var(--primary-hover)); border-radius: 8px 8px 0 0; transition: height 0.4s ease; position: relative;">
                                <div class="chart-tooltip" style="position: absolute; top: -35px; left: 50%; transform: translateX(-50%); background: #000; color: #fff; padding: 4px 8px; border-radius: 6px; font-size: 0.7rem; opacity: 0; transition: opacity 0.2s; white-space: nowrap; pointer-events: none;">
                                    GH₵ {{ number_format($data['amount'], 0) }}
                                </div>
                            </div>
                            <span style="font-size: 0.7rem; font-weight: 700; color: var(--text-secondary);">{{ $data['day'] }}</span>
                        </div>
                    @endforeach

                    {{-- Grid Lines --}}
                    <div style="position: absolute; width: 100%; height: 1px; background: var(--divider); top: 25%;"></div>
                    <div style="position: absolute; width: 100%; height: 1px; background: var(--divider); top: 50%;"></div>
                    <div style="position: absolute; width: 100%; height: 1px; background: var(--divider); top: 75%;"></div>
                </div>

                <div style="display: flex; gap: 2rem; margin-top: 2rem; align-items: center;">
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="font-size: 0.75rem; color: var(--text-secondary); font-weight: 700; text-transform: uppercase;">Monthly Target</span>
                            <span style="font-size: 0.75rem; color: var(--headings); font-weight: 800;">{{ $goalProgress }}%</span>
                        </div>
                        <div style="width: 100%; height: 8px; background: var(--surface-secondary); border-radius: 4px; overflow: hidden; border: 1px solid var(--border);">
                            <div style="width: {{ $goalProgress }}%; height: 100%; background: var(--primary); border-radius: 4px; transition: width 1s ease-out;"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Activity Feed --}}
            <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h3 style="color: var(--headings); margin: 0; font-size: 1.1rem; font-weight: 700;">Live Activity</h3>
                    @if(in_array('pos', $activeServices))
                    <a href="{{ route('product_service.pos.orders') }}" style="color: var(--primary); font-size: 0.8rem; font-weight: 700; text-decoration: none;">View All</a>
                    @endif
                </div>

                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @forelse($recentActivities as $activity)
                        <div style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem; border-radius: 12px; transition: background 0.2s;" onmouseover="this.style.background='var(--surface-secondary)'" onmouseout="this.style.background='transparent'">
                            <div style="width: 40px; height: 40px; background: var(--surface-secondary); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--text-secondary);">
                                <i class="fa-solid {{ $activity['icon'] }}"></i>
                            </div>
                            <div style="flex-grow: 1;">
                                <div style="font-weight: 700; color: var(--headings); font-size: 0.9rem;">{{ $activity['type'] }} #{{ $activity['id'] }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ $activity['customer'] }} • {{ \Carbon\Carbon::parse($activity['time'])->diffForHumans() }}</div>
                            </div>
                            <div style="font-weight: 800; color: var(--headings);">GH₵ {{ number_format($activity['amount'], 2) }}</div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 2rem; color: var(--text-secondary); font-size: 0.85rem;">No recent activities found.</div>
                    @endforelse
                </div>
            </div>
        </div>
        @endif

        {{-- Right Column: Status & Alerts --}}
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">

            {{-- Workspace Staff --}}
            <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h3 style="color: var(--headings); margin: 0; font-size: 1.1rem; font-weight: 700;">Workspace Staff</h3>
                    <a href="{{ url('invites') }}" style="color: var(--primary); font-size: 0.8rem; font-weight: 700; text-decoration: none;">Manage</a>
                </div>

                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @foreach($teamMembers as $staff)
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--surface-secondary); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; border: 1px solid var(--border);">
                                {{ $staff['initial'] }}
                            </div>
                            <div style="flex-grow: 1;">
                                <div style="font-size: 0.85rem; font-weight: 700; color: var(--headings);">{{ $staff['name'] }}</div>
                                <div style="font-size: 0.7rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600;">{{ $staff['role'] }}</div>
                            </div>
                            <div style="width: 8px; height: 8px; border-radius: 50%; background: var(--success);"></div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- CRM Mini Card --}}
            @if(in_array('crm', $activeServices))
            <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem;">
                <h3 style="color: var(--headings); margin: 0 0 1.25rem; font-size: 1.1rem; font-weight: 700;">Customer Relations</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div style="background: var(--surface-secondary); padding: 1rem; border-radius: 16px;">
                        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Interactions (7D)</div>
                        <div style="font-size: 1.25rem; font-weight: 800; color: var(--headings);">{{ $crmStats['recent_interactions'] ?? 0 }}</div>
                    </div>
                    <div style="background: var(--surface-secondary); padding: 1rem; border-radius: 16px;">
                        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Total Segments</div>
                        <div style="font-size: 1.25rem; font-weight: 800; color: var(--headings);">{{ $crmStats['active_segments'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Supply Chain Mini Card --}}
            @if(in_array('supply_chain', $activeServices))
            <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem;">
                <h3 style="color: var(--headings); margin: 0 0 1.25rem; font-size: 1.1rem; font-weight: 700;">Procurement</h3>
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; background: var(--surface-secondary); border-radius: 14px;">
                    <div style="font-size: 0.85rem; font-weight: 600; color: var(--text-secondary);">Open Purchase Orders</div>
                    <span style="font-weight: 800; color: var(--primary);">{{ $supplyChainStats['open_pos'] ?? 0 }}</span>
                </div>
            </div>
            @endif

            {{-- Inventory Alerts (Depends on Inventory) --}}
            @if(in_array('inventory', $activeServices))
            <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem;">
                <h3 style="color: var(--headings); margin: 0 0 1.25rem; font-size: 1.1rem; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-triangle-exclamation" style="color: var(--warning);"></i> Stock Alerts
                </h3>

                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @forelse($lowStock as $item)
                        <div style="background: rgba(239,68,68,0.05); padding: 1rem; border-radius: 14px; border: 1px solid rgba(239,68,68,0.1);">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                                <span style="font-weight: 700; color: var(--headings); font-size: 0.85rem; display: block; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $item['name'] }}</span>
                                <span style="font-size: 0.7rem; font-weight: 800; color: var(--danger); text-transform: uppercase;">Low</span>
                            </div>
                            <div style="font-size: 0.75rem; color: var(--text-secondary);">Stock: <strong style="color: var(--danger);">{{ $item['qty'] }}</strong> / Reorder: {{ $item['min'] }}</div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 2rem;">
                            <div style="width: 48px; height: 48px; background: rgba(34,197,94,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--success); margin: 0 auto 1rem;">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <p style="font-size: 0.85rem; color: var(--text-secondary); margin: 0;">Inventory healthy.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            @endif

        </div>
    </div>

    <style>
        .metric-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .metric-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.05); }
        .product-card:hover .chart-tooltip { opacity: 1 !important; }
        @keyframes pulse { 0% { opacity: 0.4; } 50% { opacity: 1; } 100% { opacity: 0.4; } }
        [title]:hover .chart-tooltip { opacity: 1 !important; }
    </style>
</div>
