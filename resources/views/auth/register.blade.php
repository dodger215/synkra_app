<x-layouts.auth>
    <x-ui.auth.card>
        <x-slot:header>
            <h2 class="synkra-auth-card-title">Create an Account</h2>
            <p class="synkra-auth-card-subtitle">Sign up to get started with Synkra</p>
        </x-slot:header>

        @if (session('status'))
            <x-ui.alert type="success" title="Success" message="{{ session('status') }}"/>
        @endif

        @if (session('error'))
            <x-ui.alert type="danger" title="Error" message="{{ session('error') }}"/>
        @endif

        <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf

            <!-- STEP 1: Shop Details -->
            <div id="step-1" class="synkra-form-step">
                <div class="synkra-step-indicator">
                    <span class="synkra-step active"><i class="fa-solid fa-store"></i></span>
                    <span class="synkra-step-line"></span>
                    <span class="synkra-step"><i class="fa-solid fa-user"></i></span>
                    <span class="synkra-step-line"></span>
                    <span class="synkra-step"><i class="fa-solid fa-lock"></i></span>
                </div>
                <h3 style="text-align: center; margin-bottom: 1.5rem; color: var(--headings);">Create your Workspace</h3>
                
                <div class="synkra-form-group">
                    <x-ui.input type="text" name="tenant_name" placeholder="e.g. Synkra Technologies Inc." value="{{ old('tenant_name') }}" icon="fa-solid fa-building" label="What is the name of your business?" required autofocus id="reg-tenant-name" />
                </div>
                
                <div style="margin-top: 1.5rem; margin-bottom: 1rem; width: 100%;">
                    <h4 style="margin: 0 0 0.5rem 0; color: var(--headings); font-size: 0.95rem;">Which tools do you need?</h4>
                    <p style="margin: 0; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1rem;">Select the services you want to enable. You can always change this later in settings.</p>
                    
                    <button type="button" class="synkra-btn synkra-btn-secondary" style="width: 100%; background: var(--surface); border: 1px solid var(--border); color: var(--text-primary); padding: 0.75rem; border-radius: 8px; font-weight: 600; cursor: pointer;" onclick="openSynkraModal('servicesModal')">
                        <i class="fa-solid fa-layer-group" style="margin-right: 8px; color: var(--primary);"></i> Choose Business Tools
                    </button>
                    
                    <div id="services-preview" style="margin-top: 1.25rem; display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        <span style="font-size: 0.85rem; color: var(--text-secondary); font-style: italic;">No tools selected yet...</span>
                    </div>
                </div>

                <div class="synkra-form-actions" style="margin-top: 2rem;">
                    <button type="button" class="synkra-btn synkra-btn-primary" onclick="goToStep(2)" style="width: 100%; padding: 0.75rem; border-radius: 8px; font-weight: 600; cursor: pointer;">Next Step <i class="fa-solid fa-arrow-right" style="margin-left: 8px;"></i></button>
                </div>
            </div>
            
            <!-- STEP 2: Account Details -->
            <div id="step-2" class="synkra-form-step" style="display: none;">
                <div class="synkra-step-indicator">
                    <span class="synkra-step completed" onclick="goToStep(1)" style="cursor: pointer;"><i class="fa-solid fa-check"></i></span>
                    <span class="synkra-step-line active"></span>
                    <span class="synkra-step active"><i class="fa-solid fa-user"></i></span>
                    <span class="synkra-step-line"></span>
                    <span class="synkra-step"><i class="fa-solid fa-lock"></i></span>
                </div>
                <h3 style="text-align: center; margin-bottom: 1.5rem; color: var(--headings);">Account Details</h3>
                
                <div class="synkra-form-group">
                    <x-ui.input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}" icon="fa-solid fa-user" label="Full Name" required id="reg-name" />
                </div>
                <div class="synkra-form-group">
                    <x-ui.input type="email" name="email" label="Email Address" placeholder="Email Address" value="{{ old('email') }}" icon="fa-solid fa-envelope" required id="reg-email" />
                </div>
                <div class="synkra-form-group">
                    <x-ui.input type="tel" name="phone_number" label="Phone Number" placeholder="e.g. 0241234567" value="{{ old('phone_number') }}" icon="fa-solid fa-phone" required id="reg-phone" minlength="10" maxlength="15" pattern="\+?[0-9]{10,15}" title="Please enter a valid phone number (10-15 digits)" />
                </div>
                
                <div class="synkra-form-actions" style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="button" class="synkra-btn synkra-btn-secondary" onclick="goToStep(1)" style="flex: 1; background: transparent; border: 1px solid var(--border); color: var(--text-secondary); cursor: pointer; border-radius: 8px; font-weight: 600;"><i class="fa-solid fa-arrow-left" style="margin-right: 8px;"></i> Back</button>
                    <button type="button" class="synkra-btn synkra-btn-primary" onclick="goToStep(3)" style="flex: 1; padding: 0.75rem; border-radius: 8px; font-weight: 600; cursor: pointer;">Next Step <i class="fa-solid fa-arrow-right" style="margin-left: 8px;"></i></button>
                </div>
            </div>

            <!-- STEP 3: Security -->
            <div id="step-3" class="synkra-form-step" style="display: none;">
                <div class="synkra-step-indicator">
                    <span class="synkra-step completed" onclick="goToStep(1)" style="cursor: pointer;"><i class="fa-solid fa-check"></i></span>
                    <span class="synkra-step-line active"></span>
                    <span class="synkra-step completed" onclick="goToStep(2)" style="cursor: pointer;"><i class="fa-solid fa-check"></i></span>
                    <span class="synkra-step-line active"></span>
                    <span class="synkra-step active"><i class="fa-solid fa-lock"></i></span>
                </div>
                <h3 style="text-align: center; margin-bottom: 1.5rem; color: var(--headings);">Security</h3>

                <div class="synkra-form-group">
                    <x-ui.input type="password" name="password" label="Password" placeholder="Password" icon="fa-solid fa-lock" required />
                </div>
                <div class="synkra-form-group">
                    <x-ui.input type="password" name="password_confirmation" label="Confirm Password" placeholder="Confirm Password" icon="fa-solid fa-lock" required />
                </div>

                <div class="synkra-form-actions" style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="button" class="synkra-btn synkra-btn-secondary" onclick="goToStep(2)" style="flex: 1; background: transparent; border: 1px solid var(--border); color: var(--text-secondary); cursor: pointer; border-radius: 8px; font-weight: 600;"><i class="fa-solid fa-arrow-left" style="margin-right: 8px;"></i> Back</button>
                    <x-ui.button type="submit" icon="fa-solid fa-user-plus" style="flex: 2;">Register</x-ui.button>
                </div>
            </div>

            <div id="social-auth-wrapper" style="display: none;">
              <x-ui.auth.social_auth />
            </div>

            <!-- Modals -->
            <style>#servicesModal-trigger-btn { display: none !important; }</style>
            <x-ui.modal id="servicesModal" triggerId="servicesModal-trigger-btn" title="Configure Services">
                <div style="margin-bottom: 1.5rem;">
                    <x-ui.search type="text" id="serviceSearch" name="serviceSearch" placeholder="Search modules..." icon="fa-solid fa-search" onkeyup="filterServices()" />
                </div>
                
                <div id="servicesList" style="display: flex; flex-direction: column; gap: 0.75rem; max-height: 55vh; overflow-y: auto; padding-right: 0.5rem;">
                    @foreach($services as $module => $permissions)
                        <div class="service-item" data-name="{{ strtolower(str_replace('_', ' ', $module)) }}">
                            <x-ui.form.module-select :module="$module" :permissions="$permissions" />
                        </div>
                    @endforeach
                </div>

                <x-slot:footer>
                    <button type="button" class="synkra-btn synkra-btn-primary" style="background: var(--primary); border: none; color: white; cursor: pointer; padding: 0.75rem; border-radius: 8px; font-weight: 600; width: 100%;" onclick="updateServicesPreview(); closeSynkraModal('servicesModal')">Done</button>
                </x-slot:footer>
            </x-ui.modal>

            <div class="synkra-auth-links">
                <a href="{{ route('login') }}" class="synkra-auth-link">Already have an account? Log in</a>
            </div>
        </form>

<script>
function filterServices() {
    const filter = document.getElementById('serviceSearch').value.toLowerCase();
    const items = document.querySelectorAll('.service-item');
    items.forEach(item => {
        if (item.getAttribute('data-name').includes(filter)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}

function updateServicesPreview() {
    const previewContainer = document.getElementById('services-preview');
    const selectedModules = new Set();
    
    const checkedBoxes = document.querySelectorAll('#servicesList input[type="checkbox"]:checked');
    
    checkedBoxes.forEach(cb => {
        const serviceItem = cb.closest('.service-item');
        if (serviceItem) {
            const titleElement = serviceItem.querySelector('h4');
            if (titleElement) {
                selectedModules.add(titleElement.innerText.trim());
            }
        }
    });

    previewContainer.innerHTML = '';
    
    if (selectedModules.size === 0) {
        previewContainer.innerHTML = '<span style="font-size: 0.85rem; color: var(--text-secondary); font-style: italic;">No tools selected yet...</span>';
        return;
    }

    selectedModules.forEach(moduleName => {
        const badge = document.createElement('span');
        badge.style.cssText = 'background: var(--surface-secondary); color: var(--text-primary); padding: 0.4rem 0.85rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; border: 1px solid var(--border); display: inline-flex; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05);';
        badge.innerHTML = '<i class="fa-solid fa-check" style="margin-right: 6px; color: var(--success);"></i> ' + moduleName;
        previewContainer.appendChild(badge);
    });
}

function toggleModuleDropdown(module) {
    const body = document.getElementById('body_' + module);
    const icon = document.getElementById('icon_' + module);
    if (body.style.display === 'none') {
        body.style.display = 'block';
        icon.style.transform = 'rotate(180deg)';
    } else {
        body.style.display = 'none';
        icon.style.transform = 'rotate(0deg)';
    }
}

function toggleAllPermissions(module, isChecked) {
    const checkboxes = document.querySelectorAll('.perm-checkbox-' + module + ' input[type="checkbox"]');
    checkboxes.forEach(cb => {
        cb.checked = isChecked;
    });
}

function goToStep(step) {
    if (step === 2) {
        const tenantName = document.getElementById('reg-tenant-name').value;
        if(!tenantName) {
            alert("Please enter your Shop Name.");
            return;
        }
    }
    
    if (step === 3) {
        const name = document.getElementById('reg-name').value;
        const email = document.getElementById('reg-email').value;
        if(!name || !email) {
            alert("Please fill out your full name and email to proceed.");
            return;
        }
    }
    
    document.getElementById('step-1').style.display = 'none';
    document.getElementById('step-2').style.display = 'none';
    document.getElementById('step-3').style.display = 'none';
    document.getElementById('social-auth-wrapper').style.display = step === 2 ? 'block' : 'none';
    
    document.getElementById('step-' + step).style.display = 'block';
}
</script>

<style>
    .synkra-form-group { width: 100%; margin-bottom: 1.25rem; display: flex; justify-content: center; align-items: center; }
    .synkra-auth-links { text-align: center; margin-top: 1.5rem; }
    .synkra-auth-link { color: var(--primary); text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: opacity 0.2s; }
    .synkra-auth-link:hover { opacity: 0.8; }

    /* Multi-step CSS */
    .synkra-form-step { width: 100%; animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
    .synkra-step-indicator { display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; gap: 8px; }
    .synkra-step { width: 32px; height: 32px; border-radius: 50%; background: var(--surface-secondary); color: var(--text-secondary); display: flex; align-items: center; justify-content: center; font-weight: 700; border: 2px solid var(--border); transition: all 0.3s; }
    .synkra-step.active { background: var(--primary); color: white; border-color: var(--primary); }
    .synkra-step.completed { background: var(--success); color: white; border-color: var(--success); }
    .synkra-step-line { height: 3px; width: 50px; background: var(--border); border-radius: 2px; transition: all 0.3s; }
    .synkra-step-line.active { background: var(--success); }
    
    @keyframes fadeIn { 
      from { opacity: 0; transform: translateX(10px); } 
      to { opacity: 1; transform: translateX(0); } 
    }
</style>
    </x-ui.auth.card>
</x-layouts.auth>
