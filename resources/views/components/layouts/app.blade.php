<!DOCTYPE html>
@php
    $sessionTheme = session('appearance', 'system');
    // Resolve 'system' at render time using a safe fallback of 'light'
    // Client JS will override instantly using prefers-color-scheme
    $htmlTheme = $sessionTheme === 'system' ? 'light' : $sessionTheme;
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="{{ $htmlTheme }}">
<head>
    {{-- ⚡ Blocking theme script: runs before CSS, eliminates flash-of-wrong-theme --}}
    <script>
        (function () {
      var saved = localStorage.getItem('appearance') || 'system';
      var theme = saved;
      if (saved === 'system') {
        theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      }
      document.documentElement.setAttribute('data-theme', theme);
    })();
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Flowexa') }}</title>

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts: Instrument Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') ?? '/css/app.css' }}">
    @endif

    @livewireStyles

    @stack('styles')

    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
            background-color: var(--background);
            color: var(--text-primary);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .background-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .flowexa-layout-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .flowexa-main-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            margin-left: 292px; /* 260 width + 16 left + 16 gap */
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
        }



        /* When sidebar is collapsed */
        .flowexa-main-wrapper.expanded {
            margin-left: 112px; /* 80 width + 16 left + 16 gap */
        }

        /* For guest views or when unauthenticated */
        .flowexa-main-wrapper.full-width {
            margin-left: 0;
        }

        .flowexa-main-content {
            padding: 2rem;
            flex-grow: 1;
            overflow-y: auto;
        }

        @media (max-width: 768px) {
            .flowexa-main-wrapper {
                margin-left: 0 !important;
            }
            .flowexa-main-content {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Global Page Loader -->
    <x-ui.loader />
    <div class="background-pattern">
        <x-ui.grid />
    </div>

    <div class="flowexa-layout-wrapper">
        @auth
            <!-- Sidebar Navigation -->
            <div style="position: relative; z-index: 10;">
                <x-ui.sidebar />
            </div>


            <div class="flowexa-main-wrapper" id="flowexaMainContent" style="relative; z-index: 20;">
                <!-- Top Navbar -->
                <x-ui.navbar />

                <!-- Page Content -->
                <main class="flowexa-main-content">
                    {{ $slot ?? '' }}
                    @yield('content')
                </main>
            </div>
        @else
            <!-- Guest / Unauthenticated View -->
            <div class="flowexa-main-wrapper full-width">
                <main class="flowexa-main-content" style="padding: 0;">
                    {{ $slot ?? '' }}
                    @yield('content')
                </main>
            </div>
        @endauth
    </div>

    {{-- @auth
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
    @endauth --}}
    @livewireScripts
    @stack('scripts')
</body>
</html>
