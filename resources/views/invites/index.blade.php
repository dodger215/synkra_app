<x-layouts.app title="User Management">
    <x-slot:head>
        <meta name="description" content="Manage workspace users and invitations.">
    </x-slot:head>

    <div class="flowexa-dashboard-container" style="padding: 2rem; max-width: 1000px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--headings); margin: 0 0 0.5rem 0;">User Management</h1>
                <p style="color: var(--text-secondary); margin: 0;">Invite team members, assign roles, and configure specific module permissions.</p>
            </div>
            <button onclick="openflowexaModal('inviteUserModal')" class="flowexa-btn flowexa-btn-primary" style="background: var(--primary); border: none; color: white; padding: 0.6rem 1.25rem; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                <i class="fa-solid fa-user-plus"></i> Invite User
            </button>
        </div>

        @if(session('status'))
            <x-ui.alert type="success" title="Success" message="{{ session('status') }}" style="margin-bottom: 2rem;" />
        @endif

        @if($errors->any())
            <x-ui.alert type="danger" title="Error" message="{{ $errors->first() }}" style="margin-bottom: 2rem;" />
        @endif

        <div class="flowexa-card" style="background: var(--surface); border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); border: 1px solid var(--border); overflow: hidden;">
            <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); background: var(--surface-secondary);">
                <h3 style="margin: 0; color: var(--headings); font-size: 1.1rem;">Pending Invitations</h3>
            </div>

            @if($invites->isEmpty())
                <div style="text-align: center; padding: 4rem 2rem;">
                    <div style="width: 64px; height: 64px; background: var(--surface-secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto; font-size: 1.5rem; color: var(--text-secondary);">
                        <i class="fa-solid fa-envelope-open-text"></i>
                    </div>
                    <h3 style="margin: 0 0 0.5rem 0; color: var(--text-primary);">No Pending Invites</h3>
                    <p style="color: var(--text-secondary); margin: 0 0 1.5rem 0; font-size: 0.95rem;">You haven't sent out any pending invitations yet.</p>
                </div>
            @else
                <div class="flowexa-table-container">
                    <table class="flowexa-table">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Expires</th>
                                <th style="text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invites as $invite)
                            <tr>
                                <td style="font-weight: 500; color: var(--text-primary);">{{ $invite->email }}</td>
                                <td>
                                    <span style="display: inline-block; padding: 0.25rem 0.75rem; background: var(--surface-secondary); border: 1px solid var(--border); border-radius: 99px; font-size: 0.75rem; font-weight: 600; text-transform: capitalize;">{{ str_replace('_', ' ', $invite->role) }}</span>
                                </td>
                                <td>
                                    <x-ui.badge variant="warning" pill="true">Pending</x-ui.badge>
                                </td>
                                <td style="color: var(--text-secondary); font-size: 0.9rem;">
                                    {{ \Carbon\Carbon::parse($invite->expires_at)->diffForHumans() }}
                                </td>
                                <td style="text-align: right;">
                                    <div class="flowexa-table-actions" style="justify-content: flex-end;">
                                        <form action="{{ route('invite.resend', $invite->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="flowexa-table-action-btn" style="color: var(--primary);" title="Resend Invite">
                                                <i class="fa-solid fa-paper-plane"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('invite.revoke', $invite->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to revoke this invitation?');">
                                            @csrf
                                            <button type="submit" class="flowexa-table-action-btn" style="color: var(--danger);" title="Revoke Invite">
                                                <i class="fa-solid fa-ban"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Invite User Modal -->
    <style>#inviteUserModal-trigger-btn { display: none !important; }</style>
    <x-ui.modal id="inviteUserModal" triggerId="inviteUserModal-trigger-btn" title="Invite Team Member">
        <form id="inviteUserForm" action="{{ url('invite') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <x-ui.input name="email" type="email" label="Email Address" placeholder="colleague@example.com" required />

                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-size: 0.85rem; font-weight: 600; color: var(--headings);">Primary Role</label>
                    <select name="role" style="width: 100%; padding: 0.75rem 1rem; background: var(--surface); border: 1px solid var(--border); border-radius: 8px; color: var(--text-primary); outline: none; font-size: 0.95rem; font-family: inherit;">
                        @foreach($roles as $role)
                            <option value="{{ $role }}">{{ ucwords(str_replace('_', ' ', $role)) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 0.5rem;">
                <h4 style="color: var(--headings); margin: 0 0 0.25rem 0; font-size: 1rem;">Service Module Permissions</h4>
                <p style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 1.5rem;">Select the sub-permissions this user should have in each module.</p>

                <div style="max-height: 400px; overflow-y: auto; padding-right: 0.5rem; display: flex; flex-direction: column; gap: 1rem;">
                    @foreach($permissions as $module => $actions)
                    <div class="service-item">
                        <div style="padding: 1rem; transition: background 0.2s;" onmouseover="this.style.background='var(--surface-secondary)'" onmouseout="this.style.background='transparent'">
                            <x-ui.checkbox
                                label="{{ ucwords(str_replace('_', ' ', $module)) }} Module"
                                onchange="document.getElementById('sub_perms_{{ $module }}').style.display = this.checked ? 'grid' : 'none'; const cbs = document.getElementById('sub_perms_{{ $module }}').querySelectorAll('input[type=checkbox]'); cbs.forEach(cb => cb.checked = this.checked);"
                            />
                        </div>

                        <div id="sub_perms_{{ $module }}" style="display: none; grid-template-columns: 1fr 1fr; gap: 0.75rem; padding: 1rem; border-top: 1px solid var(--border); background: var(--surface-secondary);">
                            @php
                                $groupedActions = [];
                                foreach($actions as $action => $default) {
                                    $baseAction = preg_replace('/^(view|create|edit|delete|manage|approve|report|dispose|process|verify|export|resolve|void|refund|open|close|track|cash_drawer|sync|handle|initiate|reconcile|adjust|log|send|abandoned|publish|schedule|import)_/', '', $action);
                                    if (!isset($groupedActions[$baseAction])) {
                                        $groupedActions[$baseAction] = [];
                                    }
                                    $groupedActions[$baseAction][] = $action;
                                }
                            @endphp

                            @foreach($groupedActions as $baseAction => $subActions)
                            <div>
                                <x-ui.checkbox
                                    label="Manage {{ ucwords(str_replace('_', ' ', $baseAction)) }}"
                                    onchange="const cbs = this.parentElement.querySelectorAll('.hidden-perm'); cbs.forEach(cb => cb.checked = this.checked);"
                                />
                                @foreach($subActions as $subAction)
                                    <input type="checkbox" class="hidden-perm" name="permissions[{{ $module }}][{{ $subAction }}]" value="1" style="display:none;">
                                @endforeach
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </form>

        <x-slot:footer>
            <button type="button" class="flowexa-btn flowexa-btn-secondary" style="background: transparent; border: 1px solid var(--border); color: var(--text-secondary); cursor: pointer; padding: 0.5rem 1rem; border-radius: 8px;" onclick="closeflowexaModal('inviteUserModal')">Cancel</button>
            <button type="button" class="flowexa-btn flowexa-btn-primary" style="background: var(--primary); border: none; color: white; cursor: pointer; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; display: flex; align-items: center; gap: 6px;" onclick="document.getElementById('inviteUserForm').submit()">
                <i class="fa-solid fa-paper-plane"></i> Send Invite
            </button>
        </x-slot:footer>
    </x-ui.modal>
</x-layouts.app>
