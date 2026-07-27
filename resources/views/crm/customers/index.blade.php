<x-layouts.app title="Customers">
    <div class="flowexa-dashboard-container" style="padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--headings); margin: 0; font-weight: 800;">Customer Relationship Management</h1>
                <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0;">Manage your customer base, segments, and communications.</p>
            </div>
            <a href="{{ route('crm.customers.create') }}" class="flowexa-btn" style="background: var(--primary); border: none; color: white; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-user-plus"></i> Add Customer
            </a>
        </div>

        @if(session('success'))
            <x-ui.alert type="success" title="Success" message="{{ session('success') }}" style="margin-bottom: 2rem;" />
        @endif

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--surface-secondary); border-bottom: 1px solid var(--border);">
                        <th style="text-align: left; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase;">Customer</th>
                        <th style="text-align: left; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase;">Contact</th>
                        <th style="text-align: left; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase;">Group</th>
                        <th style="text-align: left; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase;">Spent (₵)</th>
                        <th style="text-align: right; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 1.25rem 1.5rem;">
                                <div style="font-weight: 700; color: var(--headings);">{{ $customer->first_name }} {{ $customer->last_name }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-secondary);">{{ $customer->company_name ?? 'Individual' }}</div>
                            </td>
                            <td style="padding: 1.25rem 1.5rem;">
                                <div style="color: var(--text-primary);">{{ $customer->email ?? 'N/A' }}</div>
                                <div style="font-size: 0.85rem; color: var(--text-secondary);">{{ $customer->phone ?? 'N/A' }}</div>
                            </td>
                            <td style="padding: 1.25rem 1.5rem;">
                                <span style="background: var(--surface-secondary); padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">{{ strtoupper($customer->customer_group) }}</span>
                            </td>
                            <td style="padding: 1.25rem 1.5rem; color: var(--text-primary); font-weight: 600;">
                                ₵{{ number_format($customer->total_spent, 2) }}
                            </td>
                            <td style="padding: 1.25rem 1.5rem; text-align: right;">
                                <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                                    <a href="{{ route('crm.customers.show', $customer->id) }}" style="color: var(--text-secondary);"><i class="fa-solid fa-eye"></i></a>
                                    <a href="{{ route('crm.customers.edit', $customer->id) }}" style="color: var(--primary);"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <form action="{{ route('crm.customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Delete this customer?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: none; border: none; color: var(--danger); cursor: pointer;"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 4rem; text-align: center; color: var(--text-secondary);">
                                <i class="fa-solid fa-users" style="font-size: 2rem; margin-bottom: 1rem; display: block; opacity: 0.3;"></i>
                                No customers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="padding: 1.5rem;">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
