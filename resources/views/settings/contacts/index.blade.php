<x-layouts.app title="Company Contacts">
    <x-slot:head>
        <meta name="description" content="Manage your company contact details and social links.">
    </x-slot:head>

    <div class="flowexa-dashboard-container" style="padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--headings); margin: 0 0 0.5rem 0;">Company Contacts</h1>
                <p style="color: var(--text-secondary); margin: 0;">Manage your official phone numbers, emails, and social media profiles.</p>
            </div>
            <button class="flowexa-btn flowexa-btn-primary" style="background: var(--primary); border: none; color: white; cursor: pointer; padding: 0.75rem 1.5rem; border-radius: 10px; font-weight: 600;" onclick="openflowexaModal('addContactModal')">
                <i class="fa-solid fa-plus" style="margin-right: 8px;"></i> Add Contact
            </button>
        </div>

        @if(session('status'))
            <x-ui.alert type="success" title="Success" message="{{ session('status') }}" style="margin-bottom: 2rem;" />
        @endif

        @if($errors->any())
            <x-ui.alert type="danger" title="Error" message="Please fix the errors in your form submission." style="margin-bottom: 2rem;" />
        @endif

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem;">
            @forelse($contacts as $contact)
                <div class="flowexa-card" style="padding: 1.5rem; background: var(--surface); border-radius: 20px; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.06); border: 1px solid var(--border);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--surface-secondary); display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 1.25rem;">
                                @if(strtolower($contact->platform) == 'phone') <i class="fa-solid fa-phone"></i>
                                @elseif(strtolower($contact->platform) == 'email') <i class="fa-solid fa-envelope"></i>
                                @elseif(strtolower($contact->platform) == 'website') <i class="fa-solid fa-globe"></i>
                                @elseif(strtolower($contact->platform) == 'twitter' || strtolower($contact->platform) == 'x') <i class="fa-brands fa-x-twitter"></i>
                                @elseif(strtolower($contact->platform) == 'facebook') <i class="fa-brands fa-facebook"></i>
                                @elseif(strtolower($contact->platform) == 'instagram') <i class="fa-brands fa-instagram"></i>
                                @elseif(strtolower($contact->platform) == 'linkedin') <i class="fa-brands fa-linkedin"></i>
                                @else <i class="fa-solid fa-address-book"></i> @endif
                            </div>
                            <div>
                                <h3 style="margin: 0 0 0.25rem 0; color: var(--headings); text-transform: capitalize;">{{ $contact->platform }}</h3>
                                <p style="margin: 0; color: var(--text-secondary); font-size: 0.85rem; text-transform: capitalize;">{{ $contact->contact_type ?? 'General' }}</p>
                            </div>
                        </div>
                        @if($contact->is_primary)
                            <x-ui.badge type="primary">Primary</x-ui.badge>
                        @endif
                    </div>

                    <div style="margin-bottom: 1.5rem; padding: 1rem; background: var(--surface-secondary); border-radius: 12px;">
                        <span style="display: block; font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Handle / Value</span>
                        <strong style="color: var(--text-primary); font-size: 1.05rem;">{{ $contact->handle ?? 'N/A' }}</strong>

                        @if($contact->url)
                            <div style="margin-top: 0.5rem; border-top: 1px dashed var(--border); padding-top: 0.5rem;">
                                <a href="{{ $contact->url }}" target="_blank" style="color: var(--primary); font-size: 0.85rem; text-decoration: none;"><i class="fa-solid fa-arrow-up-right-from-square" style="margin-right: 4px;"></i> {{ $contact->url }}</a>
                            </div>
                        @endif
                    </div>

                    <div style="display: flex; justify-content: flex-end; border-top: 1px solid var(--border); padding-top: 1rem;">
                        <form action="{{ route('settings.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this contact?');">
                            @csrf @method('DELETE')
                            <button type="submit" style="background: transparent; border: 1px solid var(--danger); color: var(--danger); padding: 0.4rem 1rem; border-radius: 8px; cursor: pointer; font-weight: 500; transition: all 0.2s;">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 5rem 2rem; background: var(--surface); border-radius: 20px; border: 2px dashed var(--border);">
                    <div style="width: 80px; height: 80px; background: var(--surface-secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
                        <i class="fa-solid fa-address-book" style="font-size: 2.5rem; color: var(--primary);"></i>
                    </div>
                    <h3 style="margin: 0 0 0.5rem 0; color: var(--text-primary); font-size: 1.25rem;">No Contacts Found</h3>
                    <p style="margin: 0 0 2rem 0; color: var(--text-secondary); max-width: 400px; margin-left: auto; margin-right: auto;">Add official communication channels so your customers and partners can reach you.</p>
                    <button class="flowexa-btn flowexa-btn-primary" style="background: var(--primary); border: none; color: white; cursor: pointer; padding: 0.75rem 2rem; border-radius: 10px; font-weight: 600;" onclick="openflowexaModal('addContactModal')">Add First Contact</button>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Add Contact Modal -->
    <style>#addContactModal-trigger-btn { display: none !important; }</style>
    <x-ui.modal id="addContactModal" triggerId="addContactModal-trigger-btn" title="Add Contact Method">
        <form id="addContactForm" action="{{ route('settings.contacts.store') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-size: 0.85rem; font-weight: 500; color: var(--text-primary);">Platform / Type</label>
                    <select name="platform" style="width: 100%; padding: 0.75rem 1rem; background: var(--surface); border: 1px solid var(--border); border-radius: 8px; color: var(--text-primary); outline: none;" required>
                        <option value="Phone">Phone</option>
                        <option value="Email">Email</option>
                        <option value="Website">Website</option>
                        <option value="Twitter">Twitter / X</option>
                        <option value="Facebook">Facebook</option>
                        <option value="Instagram">Instagram</option>
                        <option value="LinkedIn">LinkedIn</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <x-ui.input name="contact_type" label="Department / Purpose" placeholder="e.g. Sales, Support" />
            </div>

            <div style="margin-bottom: 1rem;">
                <x-ui.input name="handle" label="Handle / Value" placeholder="e.g. +1 555-0198 or @flowexa" required />
            </div>

            <div style="margin-bottom: 1rem;">
                <x-ui.input name="url" type="url" label="Direct Link (Optional)" placeholder="https://..." />
            </div>

            <div style="margin-top: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <input type="checkbox" name="is_primary" id="is_primary" value="1" style="width: 18px; height: 18px; cursor: pointer;">
                <label for="is_primary" style="font-size: 0.9rem; color: var(--text-primary); cursor: pointer;">Set as Primary Contact</label>
            </div>
        </form>

        <x-slot:footer>
            <button type="button" class="flowexa-btn flowexa-btn-secondary" style="background: transparent; border: 1px solid var(--border); color: var(--text-secondary); cursor: pointer; padding: 0.5rem 1rem; border-radius: 8px;" onclick="closeflowexaModal('addContactModal')">Cancel</button>
            <button type="button" class="flowexa-btn flowexa-btn-primary" style="background: var(--primary); border: none; color: white; cursor: pointer; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600;" onclick="document.getElementById('addContactForm').submit()">Save Contact</button>
        </x-slot:footer>
    </x-ui.modal>
</x-layouts.app>
