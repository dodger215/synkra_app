<x-layouts.app title="Settings">
    <x-ui.grid>
        <x-slot:head>
            <meta name="description" content="Manage your workspace settings, theme, profile and billing.">
        </x-slot:head>

        <div class="flowexa-dashboard-container" style="padding:2rem;max-width:900px;margin:0 auto;">

            {{-- Page Header --}}
            <div style="margin-bottom:2.5rem;">
                <h1 style="color:var(--headings);margin:0 0 .4rem 0;font-size:1.75rem;">Settings</h1>
                <p style="color:var(--text-secondary);margin:0;">Manage your workspace, appearance, and account preferences.</p>
            </div>

            @if(session('status'))
                <x-ui.alert type="success" title="Saved" :message="session('status')" style="margin-bottom:1.5rem;" />
            @endif

            <section style="margin-bottom:2rem;">
                <h2 style="font-size:1rem;font-weight:700;color:var(--headings);margin:0 0 1rem 0;display:flex;align-items:center;gap:.5rem;">
                    <i class="fa-solid fa-palette" style="color:var(--primary);"></i> Appearance
                </h2>

                <div class="flowexa-card" style="background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:1.5rem;">
                    <p style="color:var(--text-secondary);font-size:.9rem;margin:0 0 1.25rem 0;">
                        Choose your preferred colour scheme. Your selection is saved instantly.
                    </p>

                    <div id="themeToggleGroup" style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;">

                        @foreach([
                            ['value'=>'light', 'icon'=>'fa-sun',     'label'=>'Light',  'desc'=>'Clean & bright'],
                            ['value'=>'system','icon'=>'fa-circle-half-stroke','label'=>'System','desc'=>'Match OS setting'],
                            ['value'=>'dark',  'icon'=>'fa-moon',    'label'=>'Dark',   'desc'=>'Easy on the eyes'],
                        ] as $t)
                        <button type="button"
                                id="theme-btn-{{ $t['value'] }}"
                                onclick="setTheme('{{ $t['value'] }}')"
                                class="theme-option-btn"
                                data-theme-value="{{ $t['value'] }}"
                                style="display:flex;flex-direction:column;align-items:center;gap:.6rem;padding:1.25rem 1rem;border-radius:12px;border:2px solid var(--border);background:var(--surface-secondary);cursor:pointer;transition:all .2s;text-align:center;">
                            <i class="fa-solid {{ $t['icon'] }}" style="font-size:1.4rem;color:var(--text-secondary);transition:color .2s;"></i>
                            <div>
                                <strong style="display:block;font-size:.9rem;color:var(--headings);">{{ $t['label'] }}</strong>
                                <span style="font-size:.78rem;color:var(--text-secondary);">{{ $t['desc'] }}</span>
                            </div>
                        </button>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- ══════════════════════════════════════
                 QUICK NAVIGATION CARDS
            ══════════════════════════════════════ --}}
            <section>
                <h2 style="font-size:1rem;font-weight:700;color:var(--headings);margin:0 0 1rem 0;display:flex;align-items:center;gap:.5rem;">
                    <i class="fa-solid fa-sliders" style="color:var(--primary);"></i> Configuration
                </h2>

                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1rem;">

                    @php
                    $settingsCards = [
                        [
                            'href'  => route('settings.profile.edit'),
                            'icon'  => 'fa-circle-user',
                            'color' => '#6366f1',
                            'bg'    => 'rgba(99,102,241,.1)',
                            'title' => 'Profile',
                            'desc'  => 'Update your name, email and password.',
                            'badge' => null,
                        ],
                        [
                            'href'  => route('settings.workspace.edit'),
                            'icon'  => 'fa-store',
                            'color' => 'var(--primary)',
                            'bg'    => 'rgba(249,115,22,.1)',
                            'title' => 'Shop Settings',
                            'desc'  => 'Business name, logo, currency and more.',
                            'badge' => null,
                        ],
                        [
                            'href'  => route('settings.subaccounts.index'),
                            'icon'  => 'fa-credit-card',
                            'color' => '#10b981',
                            'bg'    => 'rgba(16,185,129,.1)',
                            'title' => 'Account & Billing',
                            'desc'  => 'Manage payment methods and sub-accounts.',
                            'badge' => null,
                        ],
                        [
                            'href'  => route('settings.services.index'),
                            'icon'  => 'fa-cubes',
                            'color' => '#8b5cf6',
                            'bg'    => 'rgba(139,92,246,.1)',
                            'title' => 'Services',
                            'desc'  => 'Enable or disable workspace modules.',
                            'badge' => null,
                        ],
                        [
                            'href'  => route('settings.contacts.index'),
                            'icon'  => 'fa-address-book',
                            'color' => '#f59e0b',
                            'bg'    => 'rgba(245,158,11,.1)',
                            'title' => 'Contacts',
                            'desc'  => 'Business contacts, phones and addresses.',
                            'badge' => null,
                        ],
                        [
                            'href'  => url('/invites'),
                            'icon'  => 'fa-user-plus',
                            'color' => '#ec4899',
                            'bg'    => 'rgba(236,72,153,.1)',
                            'title' => 'Team & Invites',
                            'desc'  => 'Invite members and manage access.',
                            'badge' => null,
                        ],
                    ];
                    @endphp

                    @foreach($settingsCards as $card)
                    <a href="{{ $card['href'] }}" class="settings-nav-card"
                       style="display:flex;align-items:flex-start;gap:1rem;padding:1.25rem 1.5rem;background:var(--surface);border:1px solid var(--border);border-radius:14px;text-decoration:none;transition:all .2s;position:relative;overflow:hidden;"
                       onmouseover="this.style.borderColor='var(--primary)';this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.08)'"
                       onmouseout="this.style.borderColor='var(--border)';this.style.transform='';this.style.boxShadow=''">
                        <div style="width:42px;height:42px;border-radius:10px;background:{{ $card['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa-solid {{ $card['icon'] }}" style="color:{{ $card['color'] }};font-size:1.1rem;"></i>
                        </div>
                        <div style="min-width:0;">
                            <strong style="display:block;font-size:.95rem;color:var(--headings);margin-bottom:.2rem;">{{ $card['title'] }}</strong>
                            <span style="font-size:.82rem;color:var(--text-secondary);line-height:1.4;">{{ $card['desc'] }}</span>
                        </div>
                        <i class="fa-solid fa-chevron-right" style="color:var(--text-secondary);font-size:.75rem;margin-left:auto;align-self:center;opacity:.5;"></i>
                    </a>
                    @endforeach

                </div>
            </section>

        </div>
    </x-ui.grid>

    <style>
        .theme-option-btn.active,
        .theme-option-btn:focus-visible {
            border-color: var(--primary) !important;
            background: rgba(249,115,22,.07) !important;
        }
        .theme-option-btn.active i { color: var(--primary) !important; }
        .theme-option-btn:hover:not(.active) { border-color: var(--border); opacity: .85; }
    </style>

    <script>
        // ── Helpers ────────────────────────────────────────────────────────────
        const THEME_KEY = 'appearance';

        function resolveTheme(pref) {
            if (pref === 'system') {
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            return pref;
        }

        function applyTheme(pref) {
            const resolved = resolveTheme(pref);
            document.documentElement.setAttribute('data-theme', resolved);
            document.documentElement.setAttribute('data-theme-preference', pref);

            // Also update the navbar toggle if it exists (for immediate feedback)
            const themeText = document.getElementById('themeText');
            const themeIcon = document.querySelector('#themeToggler i');
            if (themeText && themeIcon) {
                const isDark = resolved === 'dark';
                themeText.textContent = isDark ? 'Light Mode' : 'Dark Mode';
                themeIcon.className = isDark ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
            }
        }

        function highlightActiveBtn(pref) {
            document.querySelectorAll('.theme-option-btn').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.themeValue === pref);
            });
        }

        function setTheme(pref) {
            localStorage.setItem(THEME_KEY, pref);
            applyTheme(pref);
            highlightActiveBtn(pref);

            // Persist to server session (fire & forget)
            fetch('{{ route('settings.theme.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                },
                body: JSON.stringify({ theme: pref }),
            }).catch(() => {});
        }

        // ── On page load: restore saved preference ─────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            const saved = localStorage.getItem(THEME_KEY) || '{{ session('appearance', 'system') }}';
            applyTheme(saved);
            highlightActiveBtn(saved);
        });

        // ── Cross-tab sync: when another tab changes the theme ────────────
        window.addEventListener('storage', (e) => {
            if (e.key === THEME_KEY && e.newValue) {
                applyTheme(e.newValue);
                highlightActiveBtn(e.newValue);
            }
        });

        // ── System preference changes (when mode is 'system') ─────────────
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            const current = localStorage.getItem(THEME_KEY) || 'system';
            if (current === 'system') applyTheme('system');
        });
    </script>
</x-layouts.app>

