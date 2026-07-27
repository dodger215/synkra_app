@push('styles')
<link rel="stylesheet" href="https://unpkg.com/maplibre-gl@4.0.2/dist/maplibre-gl.css" />
<style>
    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    @media (max-width: 768px) {
        .grid-2 { grid-template-columns: 1fr; }
    }
    .maplibregl-ctrl-logo, .maplibregl-ctrl-attrib { display: none !important; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/maplibre-gl@4.0.2/dist/maplibre-gl.js"></script>
@endpush

<x-layouts.auth>
    <div class="registration-layout">
        <!-- ASIDE: Progress & Description -->
        <aside class="registration-sidebar">
            <div class="sidebar-top">
                <a href="/" class="brand-link">
                    <div class="brand-logo"></div>
                    <span class="brand-name">FLOWEXA</span>
                </a>

                <div class="steps-nav">
                    <div class="step-nav-item active" id="nav-step-1">
                        <div class="step-number">1</div>
                        <div class="step-details">
                            <strong>Business Identity</strong>
                            <p>Tell us about your brand and set your visual identity.</p>
                        </div>
                    </div>

                    <div class="step-nav-item" id="nav-step-2">
                        <div class="step-number">2</div>
                        <div class="step-details">
                            <strong>Shop Location</strong>
                            <p>Set your physical location for pickup and delivery.</p>
                        </div>
                    </div>

                    <div class="step-nav-item" id="nav-step-3">
                        <div class="step-number">3</div>
                        <div class="step-details">
                            <strong>Business Tools</strong>
                            <p>Select the modules and services your business needs.</p>
                        </div>
                    </div>

                    <div class="step-nav-item" id="nav-step-4">
                        <div class="step-number">4</div>
                        <div class="step-details">
                            <strong>Owner Profile</strong>
                            <p>Set up your personal account to manage the workspace.</p>
                        </div>
                    </div>

                    <div class="step-nav-item" id="nav-step-5">
                        <div class="step-number">5</div>
                        <div class="step-details">
                            <strong>Security</strong>
                            <p>Secure your workspace with a strong password.</p>
                        </div>
                    </div>

                    <div class="step-nav-item" id="nav-step-6">
                        <div class="step-number">6</div>
                        <div class="step-details">
                            <strong>Final Setup</strong>
                            <p>Configure your supply chain role and finalize.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sidebar-help">
                <p class="help-title">Need help?</p>
                <p class="help-text">Our setup wizard is designed to get you running in under 2 minutes. Reach out if you get stuck.</p>
            </div>
        </aside>

        <!-- MAIN: Form Content -->
        <main class="registration-main">
            <div class="form-container">
                <form method="POST" action="{{ route('register') }}" id="registerForm" enctype="multipart/form-data">
                    @csrf

                    <!-- STEP 1: Business Identity -->
                    <div id="step-1" class="flowexa-form-step">
                        <header class="step-header">
                            <h2>Welcome! Let's start with your business.</h2>
                            <p>This will be the name of your marketplace shop and workspace.</p>
                        </header>

                        <div class="step-content">
                            <x-ui.input type="text" name="tenant_name" placeholder="e.g. Acme Fashion" value="{{ old('tenant_name') }}" icon="fa-solid fa-store" label="What is the name of your business?" required autofocus id="reg-tenant-name" />

                            <div class="upload-section">
                                <label class="section-label">Upload Shop Logo</label>
                                <p class="section-desc">A clear logo helps customers recognize your brand instantly.</p>
                                <div class="logo-upload-zone" onclick="document.getElementById('logo-input').click()">
                                    <input type="file" name="logo" id="logo-input" style="display: none;" accept="image/*" onchange="previewLogo(this)">
                                    <div id="logo-placeholder" class="placeholder-content">
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                        <span>Logo</span>
                                    </div>
                                    <img id="logo-preview" class="preview-img">
                                </div>
                            </div>

                            <div class="banner-section">
                                <label class="section-label">Select Marketplace Banner</label>
                                <p class="section-desc">Choose a high-quality cover for your public shop page.</p>
                                <div class="banner-grid">
                                    @foreach($bannerOptions as $option)
                                        <label class="banner-option">
                                            <input type="radio" name="banner_url" value="{{ $option }}" style="display: none;" onchange="selectBanner(this)">
                                            <img src="{{ $option }}" class="banner-img">
                                            <div class="banner-check">
                                                <i class="fa-solid fa-check"></i>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="step-actions flex-end">
                            <button type="button" class="btn-primary" onclick="goToStep(2)">
                                Continue to Location <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 2: Shop Location -->
                    <div id="step-2" class="flowexa-form-step" style="display: none;">
                        <header class="step-header">
                            <h2>Where is your shop located?</h2>
                            <p>Configure your physical address for pickup and future deliveries.</p>
                        </header>

                        <div class="step-content">
                            <div class="grid-2">
                                <div>
                                    <label class="section-label">Country</label>
                                    <select name="country" class="flowexa-input-field" style="width: 100%; margin-bottom: 1.5rem;">
                                        <option value="Ghana" selected>Ghana</option>
                                        <option value="Nigeria">Nigeria</option>
                                        <option value="Kenya">Kenya</option>
                                        <option value="South Africa">South Africa</option>
                                    </select>

                                    <x-ui.input type="text" name="city" id="reg-city" placeholder="e.g. Accra" icon="fa-solid fa-city" label="City" required />
                                </div>
                                <div>
                                    <x-ui.input type="text" name="address" id="reg-address" placeholder="e.g. 123 High Street" icon="fa-solid fa-location-dot" label="Full Address" required />
                                    <x-ui.input type="text" name="landmark" id="reg-landmark" placeholder="e.g. Opposite the Mall" icon="fa-solid fa-landmark" label="Landmark (Optional)" />
                                </div>
                            </div>

                            <div class="map-section">
                                <label class="section-label">Pin your location on the map</label>
                                <div id="reg-map" style="height: 300px; border-radius: 16px; border: 1px solid var(--border); margin-bottom: 1rem;"></div>
                                <input type="hidden" name="latitude" id="reg-lat">
                                <input type="hidden" name="longitude" id="reg-lng">
                                <p class="section-desc">Drag the marker to your exact shop location.</p>
                            </div>
                        </div>

                        <div class="step-actions flex-between">
                            <button type="button" class="btn-secondary" onclick="goToStep(1)"><i class="fa-solid fa-arrow-left"></i> Back</button>
                            <button type="button" class="btn-primary" onclick="goToStep(3)">Continue <i class="fa-solid fa-arrow-right"></i></button>
                        </div>
                    </div>

                    <!-- STEP 3: Business Tools -->
                    <div id="step-3" class="flowexa-form-step" style="display: none;">
                        <header class="step-header">
                            <h2>Which tools do you need?</h2>
                            <p>Enable the modules you want to use. You can always change this later.</p>
                        </header>

                        <div id="servicesList" class="services-grid">
                            @foreach($services as $module => $permissions)
                                <div class="service-card">
                                    <x-ui.form.module-select :module="$module" :permissions="$permissions" />
                                </div>
                            @endforeach
                        </div>

                        <div class="step-actions flex-between">
                            <button type="button" class="btn-secondary" onclick="goToStep(2)"><i class="fa-solid fa-arrow-left"></i> Back</button>
                            <button type="button" class="btn-primary" onclick="goToStep(4)">Continue <i class="fa-solid fa-arrow-right"></i></button>
                        </div>
                    </div>

                    <!-- STEP 4: Owner Profile -->
                    <div id="step-4" class="flowexa-form-step" style="display: none;">
                        <header class="step-header">
                            <h2>Account Details</h2>
                            <p>Create your admin account to manage your new workspace.</p>
                        </header>

                        <div class="step-content">
                            <x-ui.input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}" icon="fa-solid fa-user" label="Full Name" required id="reg-name" />
                            <x-ui.input type="email" name="email" label="Email Address" placeholder="Email Address" value="{{ old('email') }}" icon="fa-solid fa-envelope" required id="reg-email" />
                            <x-ui.input type="tel" name="phone_number" label="Phone Number" placeholder="e.g. 0241234567" value="{{ old('phone_number') }}" icon="fa-solid fa-phone" required id="reg-phone" minlength="10" maxlength="15" pattern="\+?[0-9]{10,15}" title="Please enter a valid phone number (10-15 digits)" />
                        </div>

                        <div class="step-actions flex-between">
                            <button type="button" class="btn-secondary" onclick="goToStep(3)"><i class="fa-solid fa-arrow-left"></i> Back</button>
                            <button type="button" class="btn-primary" onclick="goToStep(5)">Continue <i class="fa-solid fa-arrow-right"></i></button>
                        </div>
                    </div>

                    <!-- STEP 5: Security -->
                    <div id="step-5" class="flowexa-form-step" style="display: none;">
                        <header class="step-header">
                            <h2>Set your password</h2>
                            <p>Choose a strong password to protect your data.</p>
                        </header>

                        <div class="step-content">
                            <x-ui.input type="password" name="password" label="Password" placeholder="Minimum 8 characters" icon="fa-solid fa-lock" required />
                            <x-ui.input type="password" name="password_confirmation" label="Confirm Password" placeholder="Repeat password" icon="fa-solid fa-lock" required />
                        </div>

                        <div class="step-actions flex-between">
                            <button type="button" class="btn-secondary" onclick="goToStep(4)"><i class="fa-solid fa-arrow-left"></i> Back</button>
                            <button type="button" class="btn-primary" onclick="goToStep(6)">Final Step <i class="fa-solid fa-arrow-right"></i></button>
                        </div>
                    </div>

                    <!-- STEP 6: Workspace Preferences -->
                    <div id="step-6" class="flowexa-form-step" style="display: none;">
                        <header class="step-header">
                            <h2>Almost there...</h2>
                            <p>How will you use the Flowexa network?</p>
                        </header>

                        <div class="role-preference">
                            <label class="section-label">Supply Chain Role</label>

                            <div class="roles-grid">
                                <label class="role-card">
                                    <input type="radio" name="supply_chain_mode" value="buyer">
                                    <div class="role-details">
                                        <strong>Supplying from others</strong>
                                        <span>Manage procurement from our global supplier network.</span>
                                    </div>
                                </label>

                                <label class="role-card">
                                    <input type="radio" name="supply_chain_mode" value="supplier">
                                    <div class="role-details">
                                        <strong>Being a supplier</strong>
                                        <span>List your products for other businesses to buy.</span>
                                    </div>
                                </label>

                                <label class="role-card">
                                    <input type="radio" name="supply_chain_mode" value="both">
                                    <div class="role-details">
                                        <strong>Both</strong>
                                        <span>Operate as a wholesaler and a retail buyer.</span>
                                    </div>
                                </label>

                                <label class="role-card">
                                    <input type="radio" name="supply_chain_mode" value="none" checked>
                                    <div class="role-details">
                                        <strong>Skip for now</strong>
                                        <span>Standard workspace with no network features.</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="step-actions flex-between items-center mt-lg">
                            <button type="button" class="btn-secondary" onclick="goToStep(5)"><i class="fa-solid fa-arrow-left"></i> Back</button>
                            <button type="submit" class="btn-submit">
                                Create My Workspace <i class="fa-solid fa-rocket"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>

<script>
let regMap, regMarker;
const apiKey = '7a8ebe56abcf44e0be0c71f31d2fdfd7';

function initRegMap() {
    if (regMap) return;

    const center = [-0.1870, 5.6037]; // Accra [lng, lat]
    regMap = new maplibregl.Map({
        container: 'reg-map',
        style: `https://maps.geoapify.com/v1/styles/osm-bright/style.json?apiKey=${apiKey}`,
        center: center,
        zoom: 13
    });

    regMarker = new maplibregl.Marker({ draggable: true })
        .setLngLat(center)
        .addTo(regMap);

    regMarker.on('dragend', function() {
        const pos = regMarker.getLngLat();
        document.getElementById('reg-lat').value = pos.lat;
        document.getElementById('reg-lng').value = pos.lng;
    });

    // Default values
    document.getElementById('reg-lat').value = center[1];
    document.getElementById('reg-lng').value = center[0];
}

async function searchRegLocation() {
    const city = document.getElementById('reg-city').value;
    const address = document.getElementById('reg-address').value;
    const query = `${address} ${city}`.trim();

    if (!query) return;

    try {
        const response = await fetch(`https://api.geoapify.com/v1/geocode/search?text=${encodeURIComponent(query)}&apiKey=${apiKey}`);
        const data = await response.json();
        if (data.features && data.features.length > 0) {
            const coords = data.features[0].geometry.coordinates; // [lng, lat]
            regMap.setCenter(coords);
            regMap.setZoom(15);
            regMarker.setLngLat(coords);
            document.getElementById('reg-lat').value = coords[1];
            document.getElementById('reg-lng').value = coords[0];
        }
    } catch (error) {
        console.error('Geocoding error:', error);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const cityInput = document.getElementById('reg-city');
    const addressInput = document.getElementById('reg-address');

    if (cityInput) cityInput.addEventListener('change', searchRegLocation);
    if (addressInput) addressInput.addEventListener('change', searchRegLocation);
});

function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('logo-preview').src = e.target.result;
            document.getElementById('logo-preview').style.display = 'block';
            document.getElementById('logo-placeholder').style.display = 'none';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function selectBanner(input) {
    const labels = document.querySelectorAll('.banner-option');
    labels.forEach(l => {
        l.style.borderColor = 'transparent';
        l.querySelector('.banner-check').style.display = 'none';
    });

    const label = input.closest('.banner-option');
    label.style.borderColor = 'var(--primary)';
    label.querySelector('.banner-check').style.display = 'flex';
}

function goToStep(step) {
    // Validation
    if (step === 2) {
        const tenantName = document.getElementById('reg-tenant-name').value;
        if(!tenantName) {
            alert("Please enter your Shop Name.");
            return;
        }
    }

    if (step === 3) {
        const city = document.getElementById('reg-city').value;
        const address = document.getElementById('reg-address').value;
        if(!city || !address) {
            alert("Please provide your shop city and address.");
            return;
        }
    }

    if (step === 5) {
        const name = document.getElementById('reg-name').value;
        const email = document.getElementById('reg-email').value;
        if(!name || !email) {
            alert("Please fill out your full name and email to proceed.");
            return;
        }
    }

    // Hide all steps
    document.querySelectorAll('.flowexa-form-step').forEach(el => el.style.display = 'none');

    // Show current step
    document.getElementById('step-' + step).style.display = 'block';

    if (step === 2) {
        setTimeout(initRegMap, 100);
    }

    // Update Sidebar Navigation
    document.querySelectorAll('.step-nav-item').forEach((item, index) => {
        const currentNum = index + 1;
        const dot = item.querySelector('.step-number');

        if (currentNum < step) {
            // Completed
            item.style.opacity = '1';
            dot.style.background = 'var(--success)';
            dot.style.color = 'white';
            dot.innerHTML = '<i class="fa-solid fa-check"></i>';
        } else if (currentNum === step) {
            // Active
            item.style.opacity = '1';
            dot.style.background = 'var(--primary)';
            dot.style.color = 'white';
            dot.innerHTML = currentNum;
        } else {
            // Future
            item.style.opacity = '0.5';
            dot.style.background = 'var(--surface-secondary)';
            dot.style.color = 'var(--text-secondary)';
            dot.innerHTML = currentNum;
        }
    });

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Initial state for banner selection if old value exists
document.addEventListener('DOMContentLoaded', function() {
    const checkedBanner = document.querySelector('input[name="banner_url"]:checked');
    if (checkedBanner) selectBanner(checkedBanner);
});
</script>

<style>
    :root {
        --registration-sidebar-width: 380px;
    }

    .registration-layout {
        display: flex;
        flex-direction: column;
        width: 100%;
        min-height: 100vh;
        background: var(--background);
    }

    @media (min-width: 1024px) {
        .registration-layout {
            flex-direction: row;
        }
    }

    .registration-sidebar {
        width: 100%;
        background: var(--surface);
        padding: 2rem 1.5rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    @media (min-width: 1024px) {
        .registration-sidebar {
            width: var(--registration-sidebar-width);
            padding: 3rem 2.5rem;
            border-right: 1px solid var(--border);
            border-bottom: none;
            position: sticky;
            top: 0;
            height: 100vh;
            gap: 3rem;
        }
    }

    .brand-link {
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 2rem;
    }

    @media (min-width: 1024px) {
        .brand-link {
            margin-bottom: 3rem;
        }
    }

    .brand-logo {
        width: 32px;
        height: 32px;
        background: var(--primary);
        border-radius: 8px;
    }

    .brand-name {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--headings);
        letter-spacing: -0.5px;
    }

    .steps-nav {
        display: flex;
        overflow-x: auto;
        gap: 1.5rem;
        padding-bottom: 1rem;
        scrollbar-width: none;
    }

    .steps-nav::-webkit-scrollbar {
        display: none;
    }

    @media (min-width: 1024px) {
        .steps-nav {
            flex-direction: column;
            overflow-x: visible;
            gap: 2rem;
            padding-bottom: 0;
        }
    }

    .step-nav-item {
        display: flex;
        gap: 1rem;
        align-items: flex-start;
        flex-shrink: 0;
        width: 200px;
    }

    @media (min-width: 1024px) {
        .step-nav-item {
            width: auto;
        }
    }

    .step-number {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--surface-secondary);
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        flex-shrink: 0;
        font-size: 0.85rem;
        border: 1px solid var(--border);
        transition: all 0.3s;
    }

    .step-details strong {
        display: block;
        color: var(--headings);
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
    }

    .step-details p {
        margin: 0;
        font-size: 0.8rem;
        color: var(--text-secondary);
        line-height: 1.4;
    }

    @media (max-width: 1023px) {
        .step-details p {
            display: none;
        }
        .step-nav-item {
            width: auto;
            align-items: center;
        }
    }

    .sidebar-help {
        margin-top: auto;
        padding: 1.5rem;
        background: var(--surface-secondary);
        border-radius: 16px;
        border: 1px solid var(--border);
        display: none;
    }

    @media (min-width: 1024px) {
        .sidebar-help {
            display: block;
        }
    }

    .help-title {
        margin: 0;
        font-size: 0.85rem;
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .help-text {
        margin: 0;
        font-size: 0.8rem;
        color: var(--text-secondary);
        line-height: 1.5;
    }

    .registration-main {
        flex: 1;
        padding: 2rem 1rem;
        overflow-y: auto;
    }

    @media (min-width: 768px) {
        .registration-main {
            padding: 3rem 4rem;
        }
    }

    @media (min-width: 1024px) {
        .registration-main {
            padding: 4rem 8%;
        }
    }

    .form-container {
        max-width: 720px;
        margin: 0 auto;
    }

    @media (min-width: 1024px) {
        .form-container {
            margin: 0;
        }
    }

    .step-header {
        margin-bottom: 2rem;
    }

    @media (min-width: 768px) {
        .step-header {
            margin-bottom: 3rem;
        }
    }

    .step-header h2 {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--headings);
        margin-bottom: 0.75rem;
        letter-spacing: -0.5px;
    }

    @media (min-width: 768px) {
        .step-header h2 {
            font-size: 2rem;
        }
    }

    .step-header p {
        color: var(--text-secondary);
        font-size: 1rem;
    }

    .step-content {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    @media (min-width: 768px) {
        .step-content {
            gap: 2.5rem;
        }
    }

    .section-label {
        display: block;
        font-weight: 700;
        color: var(--headings);
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .section-desc {
        margin: 0 0 1.25rem 0;
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    .logo-upload-zone {
        width: 120px;
        height: 120px;
        border: 2px dashed var(--border);
        border-radius: 16px;
        position: relative;
        overflow: hidden;
        background: var(--surface);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .placeholder-content {
        text-align: center;
        color: var(--text-secondary);
    }

    .placeholder-content i {
        font-size: 1.5rem;
        display: block;
        margin-bottom: 0.5rem;
    }

    .placeholder-content span {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .preview-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: none;
    }

    .banner-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    @media (min-width: 640px) {
        .banner-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    .banner-option {
        position: relative;
        cursor: pointer;
        border-radius: 12px;
        overflow: hidden;
        border: 3px solid transparent;
        transition: all 0.2s;
        aspect-ratio: 16/9;
        display: block;
    }

    .banner-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .banner-check {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 24px;
        height: 24px;
        background: var(--primary);
        border-radius: 50%;
        color: white;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
    }

    .services-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    @media (min-width: 640px) {
        .services-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    .service-card {
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 1rem;
        background: var(--surface);
        transition: all 0.2s;
        position: relative;
    }

    @media (min-width: 768px) {
        .service-card {
            padding: 1.5rem;
        }
    }

    .step-actions {
        margin-top: 3rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    @media (min-width: 640px) {
        .step-actions {
            flex-direction: row;
            margin-top: 4rem;
        }
    }

    .flex-end { justify-content: flex-end; }
    .flex-between { justify-content: space-between; }
    .items-center { align-items: center; }

    .btn-primary {
        padding: 1rem 2rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        background: var(--primary);
        color: white;
        transition: transform 0.2s, opacity 0.2s;
    }

    .btn-primary:active { transform: scale(0.98); }

    .btn-secondary {
        padding: 1rem 2rem;
        border-radius: 12px;
        background: transparent;
        border: 1px solid var(--border);
        color: var(--text-secondary);
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-submit {
        padding: 1.25rem 2rem;
        border-radius: 12px;
        font-weight: 800;
        font-size: 1.1rem;
        border: none;
        cursor: pointer;
        background: var(--primary);
        color: white;
        box-shadow: 0 10px 25px -5px rgba(249, 115, 22, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @media (min-width: 768px) {
        .btn-submit {
            padding: 1.25rem 4rem;
        }
    }

    .role-preference {
        background: var(--surface-secondary);
        padding: 1.5rem;
        border-radius: 16px;
        border: 1px solid var(--border);
    }

    @media (min-width: 768px) {
        .role-preference {
            padding: 2rem;
        }
    }

    .roles-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .role-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--surface);
        cursor: pointer;
        transition: all 0.2s;
    }

    @media (min-width: 768px) {
        .role-card {
            padding: 1.25rem;
        }
    }

    .role-card input {
        width: 18px;
        height: 18px;
        accent-color: var(--primary);
    }

    .role-details strong {
        display: block;
        color: var(--headings);
    }

    .role-details span {
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    .mt-lg { margin-top: 3rem; }
    @media (min-width: 768px) { .mt-lg { margin-top: 4rem; } }

    .service-card:has(input[type="checkbox"]:checked) {
        border-color: var(--primary) !important;
        background: rgba(249, 115, 22, 0.05) !important;
        box-shadow: 0 4px 20px -5px rgba(249, 115, 22, 0.1);
    }

    .role-card:has(input[type="radio"]:checked) {
        border-color: var(--primary) !important;
        background: rgba(249, 115, 22, 0.05) !important;
    }

    .flowexa-form-step {
        animation: slideUp 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Override input styles for registration context if needed */
    .flowexa-input-field {
        padding: 0.875rem 1rem !important;
    }

    .flowexa-input-field.flowexa-input-has-icon {
        padding-left: 2.75rem !important;
    }

    .flowexa-input-group {
        max-width: none !important;
    }
</style>

</x-layouts.auth>
