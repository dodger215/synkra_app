<x-layouts.app title="Dashboard">

     <x-slot:head>
        <meta name="description" content="flowexa Dashboard - Monitor your workspace metrics and system status in real-time.">
        <meta name="keywords" content="flowexa, Dashboard, Metrics, System Status, Realtime Sync">
        <meta name="author" content="flowexa Team">
    </x-slot:head>

    <x-ui.grid>
        <livewire:dashboard />
    </x-ui.grid>

    @auth
        @php
            $user = auth()->user();
            $hasTenant = (bool) $user->tenant_id;
            $hasSubaccounts = $hasTenant ? $user->tenant->subaccounts()->exists() : false;

            $roleValue = $user->role instanceof \App\Enums\UserRole ? $user->role->value : $user->role;
            $isOwnerOrAdmin = $roleValue === \App\Enums\UserRole::OWNER->value || $roleValue === \App\Enums\UserRole::ADMIN->value;
            $canManageBilling = $isOwnerOrAdmin || (isset($user->permissions['settings']['manage_billing']) && $user->permissions['settings']['manage_billing']);
        @endphp

        @if(!$hasTenant)
            <style>
              #missingTenantModal-trigger-btn { display: none !important; }
            </style>
            <x-ui.modal id="missingTenantModal" triggerId="missingTenantModal-trigger-btn" title="">
              <div style="text-align: center; padding: 1rem 0 0 0;">
                  <div class="flowexa-animated-icon" style="font-size: 3.5rem; color: var(--primary); margin-bottom: 1.5rem;">
                      <i class="fa-solid fa-rocket fa-bounce" style="--fa-animation-duration: 2s; --fa-bounce-jump-scale-x: 1; --fa-bounce-jump-scale-y: 1;"></i>
                  </div>
                  <h2 style="color: var(--headings); margin: 0 0 0.5rem 0; font-size: 1.35rem;">Complete your Workspace</h2>
                  <p style="margin: 0; color: var(--text-secondary); font-size: 0.95rem; line-height: 1.6;">Welcome to flowexa! You're almost there. Please name your workspace and provide your contact details to continue.</p>

                  <form id="completeRegistrationForm" action="{{ route('settings.workspace.update') }}" method="POST" style="margin-top: 2rem; text-align: left;">
                      @csrf
                      <div style="margin-bottom: 1.25rem;">
                          <x-ui.input name="name" label="Workspace Name" placeholder="e.g. Acme Corp" required icon="fa-solid fa-building" />
                      </div>
                      <div style="margin-bottom: 1.25rem;">
                          <x-ui.input type="tel" name="phone_number" label="Phone Number" placeholder="e.g. 0241234567" value="{{ old('phone_number', $user->phone_number) }}" required icon="fa-solid fa-phone" />
                      </div>

                      <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1rem;">You can enable more tools and services in your workspace settings later.</p>
                  </form>
              </div>

              <x-slot:footer>
                <div style="display: flex; gap: 1rem; width: 100%; justify-content: center; margin-top: 1.5rem;">
                  <button type="submit" form="completeRegistrationForm" class="flowexa-btn flowexa-btn-primary" style="background: var(--primary); border: none; color: white; cursor: pointer; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 600; flex: 1;">Create Workspace</button>
                </div>
              </x-slot:footer>
            </x-ui.modal>

            <script>
              document.addEventListener('DOMContentLoaded', function() {
                  setTimeout(() => {
                      if (typeof openflowexaModal === 'function') {
                          openflowexaModal('missingTenantModal');
                      }
                  }, 600);
              });
            </script>
        @elseif(!$hasSubaccounts)
            <style>
              #missingSubaccountsModal-trigger-btn { display: none !important; }
            </style>
            <x-ui.modal id="missingSubaccountsModal" triggerId="missingSubaccountsModal-trigger-btn" title="">
              <div style="text-align: center; padding: 1rem 0 0 0;">
                  <div class="flowexa-animated-icon" style="font-size: 3.5rem; color: var(--warning); margin-bottom: 1.5rem;">
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
                  <button type="button" class="flowexa-btn flowexa-btn-secondary" style="background: var(--surface-secondary); border: none; color: var(--text-primary); cursor: pointer; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 600; flex: 1;" onclick="closeflowexaModal('missingSubaccountsModal')">Remind Me Later</button>

                  @if($canManageBilling)
                  <a href="{{ route('settings.subaccounts.index') }}" style="text-decoration: none; flex: 1;">
                    <button type="button" class="flowexa-btn flowexa-btn-primary" style="background: var(--primary); border: none; color: white; cursor: pointer; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 600; width: 100%;">Set up Subaccounts</button>
                  </a>
                  @endif
                </div>
              </x-slot:footer>
            </x-ui.modal>

            <script>
              document.addEventListener('DOMContentLoaded', function() {
                  setTimeout(() => {
                      if (typeof openflowexaModal === 'function') {
                          openflowexaModal('missingSubaccountsModal');
                      }
                  }, 600);
              });
            </script>
        @endif
    @endauth
</x-layouts.app>
