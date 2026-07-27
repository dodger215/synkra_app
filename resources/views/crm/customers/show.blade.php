<x-layouts.app title="Customer Details">
    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
        <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <a href="{{ route('crm.customers.index') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 8px; margin-bottom: 1rem;">
                    <i class="fa-solid fa-arrow-left"></i> Back to CRM
                </a>
                <h1 style="color: var(--headings); margin: 0; font-weight: 800;">{{ $customer->first_name }} {{ $customer->last_name }}</h1>
                <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0;">Customer since {{ $customer->created_at->format('M Y') }}</p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <button onclick="document.getElementById('messageModal').style.display='flex'" class="flowexa-btn" style="background: var(--primary); border: none; color: white; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-paper-plane"></i> Send Message
                </button>
                <a href="{{ route('crm.customers.edit', $customer->id) }}" class="flowexa-btn" style="background: var(--surface); border: 1px solid var(--border); color: var(--text-primary); padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none;">Edit Profile</a>
            </div>
        </div>

        @if(session('success'))
            <x-ui.alert type="success" title="Success" message="{{ session('success') }}" style="margin-bottom: 2rem;" />
        @endif
        @if(session('error'))
            <x-ui.alert type="danger" title="Error" message="{{ session('error') }}" style="margin-bottom: 2rem;" />
        @endif

        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
            {{-- Profile Sidebar --}}
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem;">
                    <h3 style="color: var(--headings); margin: 0 0 1.5rem 0; font-size: 1.1rem; font-weight: 700;">Contact Details</h3>
                    <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                        <div>
                            <div style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Email</div>
                            <div style="color: var(--headings); font-weight: 600;">{{ $customer->email ?? 'Not provided' }}</div>
                        </div>
                        <div>
                            <div style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Phone</div>
                            <div style="color: var(--headings); font-weight: 600;">{{ $customer->phone ?? 'Not provided' }}</div>
                        </div>
                        <div>
                            <div style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Company</div>
                            <div style="color: var(--headings); font-weight: 600;">{{ $customer->company_name ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <div class="flowexa-card" style="background: var(--primary); color: white; border-radius: 24px; padding: 1.5rem;">
                    <h3 style="color: white; margin: 0 0 1rem 0; font-size: 1.1rem; font-weight: 700;">Loyalty Status</h3>
                    <div style="font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem;">{{ number_format($customer->loyalty_points) }}</div>
                    <div style="font-size: 0.9rem; font-weight: 600; opacity: 0.9;">Total points earned</div>
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.2);">
                        Tier: <strong>{{ ucfirst($customer->tier) }}</strong>
                    </div>
                </div>
            </div>

            {{-- Analytics & History --}}
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="flowexa-card" style="background: var(--surface); padding: 1.5rem; border-radius: 20px; border: 1px solid var(--border);">
                        <div style="color: var(--text-secondary); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">Total Spent</div>
                        <div style="color: var(--headings); font-size: 1.5rem; font-weight: 800;">₵{{ number_format($customer->total_spent, 2) }}</div>
                    </div>
                    <div class="flowexa-card" style="background: var(--surface); padding: 1.5rem; border-radius: 20px; border: 1px solid var(--border);">
                        <div style="color: var(--text-secondary); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">Total Orders</div>
                        <div style="color: var(--headings); font-size: 1.5rem; font-weight: 800;">{{ $customer->total_orders }}</div>
                    </div>
                </div>

                <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); padding: 1.5rem;">
                    <h3 style="color: var(--headings); margin: 0 0 1.5rem 0; font-size: 1.1rem; font-weight: 700;">Recent Activity</h3>
                    <p style="text-align: center; padding: 2rem; color: var(--text-secondary);">Customer order history and interactions will appear here.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Message Modal --}}
    <div id="messageModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 1rem;">
        <div style="background: var(--surface); border-radius: 24px; max-width: 500px; width: 100%; border: 1px solid var(--border); box-shadow: 0 20px 50px rgba(0,0,0,0.2); overflow: hidden;">
            <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; color: var(--headings); font-weight: 800;">Send Message</h3>
                <button onclick="document.getElementById('messageModal').style.display='none'" style="background: none; border: none; color: var(--text-secondary); cursor: pointer; font-size: 1.25rem;"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form action="{{ route('crm.customers.message', $customer->id) }}" method="POST" style="padding: 1.5rem;">
                @csrf
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">Communication Channel</label>
                    <select name="type" required style="width: 100%; padding: 0.75rem; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary);">
                        <option value="email">Email Only</option>
                        <option value="sms">SMS Only</option>
                        <option value="both">Both Email & SMS</option>
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">Subject (For Email)</label>
                    <input type="text" name="subject" value="Message from {{ Auth::user()->tenant->name }}" style="width: 100%; padding: 0.75rem; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary);">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">Message Body</label>
                    <textarea name="message" required rows="5" style="width: 100%; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary); padding: 0.75rem; font-family: inherit; resize: vertical;"></textarea>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="button" onclick="document.getElementById('messageModal').style.display='none'" style="background: var(--surface-secondary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer;">Cancel</button>
                    <button type="submit" class="flowexa-btn" style="background: var(--primary); border: none; color: white; padding: 0.75rem 2rem; border-radius: 12px; font-weight: 700; cursor: pointer;">
                        Dispatch Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
