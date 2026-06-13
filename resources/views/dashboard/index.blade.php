@php
  $user = auth()->user();
@endphp

<x-layouts.app title="Dashboard">
     <x-slot:head>
        <meta name="description" content="Synkra Dashboard - Monitor your workspace metrics and system status in real-time.">
        <meta name="keywords" content="Synkra, Dashboard, Metrics, System Status, Realtime Sync">
        <meta name="author" content="Synkra Team">
    </x-slot:head>

    <div class="synkra-dashboard-container" style="padding: 2rem;">
        <h1 style="color: var(--headings); margin-bottom: 2rem;">Workspace Dashboard</h1>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <x-ui.card title="Total Products" subtitle="Active Inventory">
                <h2 id="metric-products" style="font-size: 2.5rem; margin: 0; color: var(--primary);">--</h2>
            </x-ui.card>
            
            <x-ui.card title="Total Orders" subtitle="POS & Ecommerce">
                <h2 id="metric-orders" style="font-size: 2.5rem; margin: 0; color: var(--secondary);">--</h2>
            </x-ui.card>
            
            <x-ui.card title="Transactions" subtitle="Lifetime Count">
                <h2 id="metric-transactions" style="font-size: 2.5rem; margin: 0; color: var(--success);">--</h2>
            </x-ui.card>

            <x-ui.card title="Team Members" subtitle="Active Users">
                <h2 id="metric-users" style="font-size: 2.5rem; margin: 0; color: var(--warning);">--</h2>
            </x-ui.card>
        </div>

        <x-ui.card title="System Status" subtitle="Realtime Sync Connection">
            <div id="sync-status" style="display: flex; align-items: center; gap: 10px; color: var(--text-secondary); font-weight: 500;">
                <i class="fa-solid fa-circle-notch fa-spin"></i> Connecting to metrics feed...
            </div>
        </x-ui.card>
    </div>


    @auth
        @php
            $user = auth()->user();
            $hasSubaccounts = $user->tenant ? $user->tenant->subaccounts()->exists() : true;
            
            $roleValue = $user->role instanceof \App\Enums\UserRole ? $user->role->value : $user->role;
            $isOwnerOrAdmin = $roleValue === \App\Enums\UserRole::OWNER->value || $roleValue === \App\Enums\UserRole::ADMIN->value;
            $canManageBilling = $isOwnerOrAdmin || (isset($user->permissions['settings']['manage_billing']) && $user->permissions['settings']['manage_billing']);
        @endphp
        @if(!$hasSubaccounts)
            <style>
              #missingSubaccountsModal-trigger-btn { display: none !important; }
            </style>
            <x-ui.modal id="missingSubaccountsModal" triggerId="missingSubaccountsModal-trigger-btn" title="">
              <div style="text-align: center; padding: 1rem 0 0 0;">
                  <div class="synkra-animated-icon" style="font-size: 3.5rem; color: var(--warning); margin-bottom: 1.5rem;">
                      <i class="fa-solid fa-triangle-exclamation fa-bounce" style="--fa-animation-duration: 2s; --fa-bounce-jump-scale-x: 1; --fa-bounce-jump-scale-y: 1;"></i>
                  </div>
                  <h2 style="color: var(--headings); margin: 0 0 0.5rem 0; font-size: 1.35rem;">Missing Billing Details</h2>
                  
                  @if($canManageBilling)
                    <p style="margin: 0; color: var(--text-secondary); font-size: 0.95rem; line-height: 1.6;">You have not configured your billing subaccounts yet. You must set up your subaccounts to start accepting payments securely across your workspace.</p>
                  @else
                    <p style="margin: 0; color: var(--text-secondary); font-size: 0.95rem; line-height: 1.6;">Your workspace has not configured its billing subaccounts yet. Please contact your workspace administrator to set up subaccounts and start accepting payments securely.</p>
                  @endif
              </div>
              
              <x-slot:footer>
                <div style="display: flex; gap: 1rem; width: 100%; justify-content: center; margin-top: 1.5rem;">
                  <button type="button" class="synkra-btn synkra-btn-secondary" style="background: var(--surface-secondary); border: none; color: var(--text-primary); cursor: pointer; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 600; flex: 1;" onclick="closeSynkraModal('missingSubaccountsModal')">Remind Me Later</button>
                  
                  @if($canManageBilling)
                  <a href="{{ route('settings.subaccounts.index') }}" style="text-decoration: none; flex: 1;">
                    <button type="button" class="synkra-btn synkra-btn-primary" style="background: var(--primary); border: none; color: white; cursor: pointer; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 600; width: 100%;">Set up Subaccounts</button>
                  </a>
                  @endif
                </div>
              </x-slot:footer>
            </x-ui.modal>

            <script>
              document.addEventListener('DOMContentLoaded', function() {
                  setTimeout(() => {
                      if (typeof openSynkraModal === 'function') {
                          openSynkraModal('missingSubaccountsModal');
                      }
                  }, 600);
              });
            </script>
        @endif
    @endauth
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fetchMetrics = async () => {
                try {
                    const response = await fetch('{{ route('dashboard.metrics') }}');
                    if (!response.ok) throw new Error('Network response was not ok');
                    const data = await response.json();
                    
                    document.getElementById('metric-products').innerText = data.metrics.products_count;
                    document.getElementById('metric-orders').innerText = data.metrics.orders_count;
                    document.getElementById('metric-transactions').innerText = data.metrics.transactions_count;
                    document.getElementById('metric-users').innerText = data.metrics.users_count;

                    const statusEl = document.getElementById('sync-status');
                    statusEl.innerHTML = `<i class="fa-solid fa-bolt" style="color: var(--success);"></i> Live Sync Active (Last updated: ${new Date(data.timestamp).toLocaleTimeString()})`;
                } catch (error) {
                    console.error('Error fetching dashboard metrics:', error);
                    const statusEl = document.getElementById('sync-status');
                    statusEl.innerHTML = `<i class="fa-solid fa-triangle-exclamation" style="color: var(--danger);"></i> Sync disconnected. Retrying...`;
                }
            };

            // Initial fetch
            fetchMetrics();

            // Realtime polling every 5 seconds
            setInterval(fetchMetrics, 5000);
        });
    </script>
</x-layouts.app>
