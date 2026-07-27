<x-layouts.app title="Supplier Approvals">
    <div class="flowexa-dashboard-container" style="padding: 2rem;">
        <div style="margin-bottom: 2rem;">
            <h1 style="color: var(--headings); margin: 0; font-weight: 800;">Connection Requests</h1>
            <p style="color: var(--text-secondary); margin: 0.25rem 0 0 0;">Manage requests from other shops to connect with your supply chain.</p>
        </div>

        @if(session('success'))
            <x-ui.alert type="success" title="Success" message="{{ session('success') }}" style="margin-bottom: 2rem;" />
        @endif

        <div class="flowexa-card" style="background: var(--surface); border-radius: 24px; border: 1px solid var(--border); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--surface-secondary); border-bottom: 1px solid var(--border);">
                        <th style="text-align: left; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">Shop Name</th>
                        <th style="text-align: left; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">Requested At</th>
                        <th style="text-align: left; padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($incomingRequests as $request)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 1.25rem 1.5rem;">
                                <div style="font-weight: 700; color: var(--headings);">{{ $request->tenant->name }}</div>
                                <div style="font-size: 0.85rem; color: var(--text-secondary);">Subdomain: {{ $request->tenant->subdomain ?? 'N/A' }}</div>
                            </td>
                            <td style="padding: 1.25rem 1.5rem; color: var(--text-primary);">
                                {{ $request->created_at->format('M d, Y') }}
                            </td>
                            <td style="padding: 1.25rem 1.5rem;">
                                <div style="display: flex; gap: 0.75rem;">
                                    <form action="{{ route('supply_chain.approvals.approve', $request->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="flowexa-btn" style="background: rgba(34, 197, 94, 0.1); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.2); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; cursor: pointer;">
                                            Approve
                                        </button>
                                    </form>
                                    <button type="button" onclick="openRejectModal('{{ $request->id }}', '{{ $request->tenant->name }}')" class="flowexa-btn" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; cursor: pointer;">
                                        Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="padding: 3rem; text-align: center; color: var(--text-secondary);">
                                <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                                No pending connection requests.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div id="rejectModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; padding: 1rem;">
        <div style="background: var(--surface); border-radius: 24px; max-width: 500px; width: 100%; border: 1px solid var(--border); box-shadow: 0 20px 50px rgba(0,0,0,0.2); overflow: hidden;">
            <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; color: var(--headings); font-weight: 800;">Reject Connection Request</h3>
                <button onclick="closeRejectModal()" style="background: none; border: none; color: var(--text-secondary); cursor: pointer; font-size: 1.25rem;"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form id="rejectForm" method="POST" style="padding: 1.5rem;">
                @csrf
                <div style="margin-bottom: 1.5rem;">
                    <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1rem;">Please provide a reason for rejecting the request from <strong id="rejectPartnerName" style="color: var(--headings);"></strong>.</p>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--headings); margin-bottom: 0.5rem;">Rejection Reason</label>
                    <textarea name="reason" required rows="3" style="width: 100%; border-radius: 12px; border: 1px solid var(--border); background: var(--surface-secondary); color: var(--text-primary); padding: 0.75rem; font-family: inherit; resize: vertical;" placeholder="e.g. We are currently not accepting new partners..."></textarea>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="button" onclick="closeRejectModal()" style="background: var(--surface-secondary); border: 1px solid var(--border); color: var(--text-primary); padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer;">Cancel</button>
                    <button type="submit" class="flowexa-btn" style="background: var(--danger); border: none; color: white; padding: 0.75rem 2rem; border-radius: 12px; font-weight: 700; cursor: pointer;">
                        Reject Request
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal(id, name) {
            const modal = document.getElementById('rejectModal');
            const form = document.getElementById('rejectForm');
            const nameSpan = document.getElementById('rejectPartnerName');

            form.action = `/supply-chain/approvals/${id}/reject`;
            nameSpan.innerText = name;
            modal.style.display = 'flex';
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('rejectModal');
            if (event.target == modal) {
                closeRejectModal();
            }
        }
    </script>
</x-layouts.app>
