<x-layouts.app title="Billing & Subaccounts">
    <x-slot:head>
        <meta name="description" content="Manage your billing details and payment subaccounts.">
    </x-slot:head>

    <div class="synkra-dashboard-container" style="padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--headings); margin: 0 0 0.5rem 0;">Billing Subaccounts</h1>
                <p style="color: var(--text-secondary); margin: 0;">Configure your settlement accounts to receive payouts.</p>
            </div>
            <button class="synkra-btn synkra-btn-primary" style="background: var(--primary); border: none; color: white; cursor: pointer; padding: 0.75rem 1.5rem; border-radius: 10px; font-weight: 600;" onclick="openSynkraModal('addSubaccountModal')">
                <i class="fa-solid fa-plus" style="margin-right: 8px;"></i> Add Subaccount
            </button>
        </div>

        @if(session('status'))
            <x-ui.alert type="success" title="Success" message="{{ session('status') }}" style="margin-bottom: 2rem;" />
        @endif

        @if($errors->any())
            <x-ui.alert type="danger" title="Error" message="Please fix the errors in your form submission." style="margin-bottom: 2rem;" />
        @endif

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem;">
            @forelse($subaccounts as $subaccount)
                <div class="synkra-card" style="padding: 1.5rem; background: var(--surface); border-radius: 20px; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.06); border: 1px solid var(--border);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                        <div>
                            <h3 style="margin: 0 0 0.25rem 0; color: var(--headings);">{{ $subaccount->bank_name ?: 'Bank Account' }}</h3>
                            <p style="margin: 0; color: var(--text-secondary); font-family: monospace; font-size: 1.1rem; letter-spacing: 1px;">•••• {{ substr($subaccount->account_number, -4) ?: 'XXXX' }}</p>
                        </div>
                        <x-ui.badge type="{{ $subaccount->is_active ? 'success' : 'warning' }}">
                            {{ $subaccount->is_active ? 'Active' : 'Inactive' }}
                        </x-ui.badge>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1.5rem; padding: 1rem; background: var(--surface-secondary); border-radius: 12px;">
                        <div>
                            <span style="display: block; font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Settlement</span>
                            <strong style="color: var(--text-primary); font-size: 1.1rem;">{{ ucfirst(strtolower($subaccount->settlement_schedule)) }}</strong>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 0.5rem; border-top: 1px solid var(--border); padding-top: 1rem;">
                        <form action="{{ route('settings.subaccounts.destroy', $subaccount->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this settlement account?');">
                            @csrf @method('DELETE')
                            <button type="submit" style="background: transparent; border: 1px solid var(--danger); color: var(--danger); padding: 0.4rem 1rem; border-radius: 8px; cursor: pointer; font-weight: 500; transition: all 0.2s;">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 5rem 2rem; background: var(--surface); border-radius: 20px; border: 2px dashed var(--border);">
                    <div style="width: 80px; height: 80px; background: var(--surface-secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
                        <i class="fa-solid fa-building-columns" style="font-size: 2.5rem; color: var(--primary);"></i>
                    </div>
                    <h3 style="margin: 0 0 0.5rem 0; color: var(--text-primary); font-size: 1.25rem;">No Subaccounts Configured</h3>
                    <p style="margin: 0 0 2rem 0; color: var(--text-secondary); max-width: 400px; margin-left: auto; margin-right: auto;">You need to add at least one settlement account to receive payments from your customers.</p>
                    <button class="synkra-btn synkra-btn-primary" style="background: var(--primary); border: none; color: white; cursor: pointer; padding: 0.75rem 2rem; border-radius: 10px; font-weight: 600;" onclick="openSynkraModal('addSubaccountModal')">Configure Now</button>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Add Subaccount Modal -->
    <style>#addSubaccountModal-trigger-btn { display: none !important; }</style>
    <x-ui.modal id="addSubaccountModal" triggerId="addSubaccountModal-trigger-btn" title="Add Settlement Account">
        <form id="addSubaccountForm" action="{{ route('settings.subaccounts.store') }}" method="POST">
            @csrf
            
            <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 0; margin-bottom: 1.5rem;">Enter the banking details where you wish to receive your payouts.</p>

            <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; background: var(--surface-secondary); padding: 0.35rem; border-radius: 12px;">
                <button type="button" id="btnTypeBank" style="flex: 1; padding: 0.6rem; border-radius: 8px; border: none; background: var(--surface); box-shadow: 0 2px 8px rgba(0,0,0,0.06); color: var(--text-primary); font-weight: 600; cursor: pointer; transition: all 0.2s;" onclick="toggleAccountType('bank')">Bank Account</button>
                <button type="button" id="btnTypeMomo" style="flex: 1; padding: 0.6rem; border-radius: 8px; border: none; background: transparent; color: var(--text-secondary); font-weight: 600; cursor: pointer; transition: all 0.2s;" onclick="toggleAccountType('momo')">Mobile Money</button>
            </div>

            <input type="hidden" id="account_type_toggle" value="bank">
            
            <!-- Hidden inputs that actually submit the valid data -->
            <input type="hidden" name="bank_code" id="final_bank_code">
            <input type="hidden" name="bank_name" id="final_bank_name">
            <input type="hidden" name="account_number" id="final_account_number">
            <input type="hidden" name="account_name" id="final_account_name">

            <div id="bankSection">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <x-ui.input name="input_bank_code" id="input_bank_code" label="Bank / Routing Code" placeholder="e.g. 044 (Access Bank)" />
                    <x-ui.input name="input_bank_account" id="input_bank_account" label="Account Number" placeholder="1234567890" />
                </div>
            </div>

            <div id="momoSection" style="display: none;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label style="font-size: 0.85rem; font-weight: 500; color: var(--text-primary);">Mobile Network</label>
                        <select id="input_momo_network" style="width: 100%; padding: 0.75rem 1rem; background: var(--surface); border: 1px solid var(--border); border-radius: 8px; color: var(--text-primary); outline: none; font-family: inherit;">
                            <option value="MTN">MTN Mobile Money</option>
                            <option value="VOD">Telcel Cash</option>
                            <option value="ATL">AirtelTigo Money</option>
                        </select>
                    </div>
                    <x-ui.input name="input_momo_phone" id="input_momo_phone" label="Phone Number" placeholder="0241234567" />
                </div>
            </div>

            <div style="margin-bottom: 1.5rem; display: flex; justify-content: flex-end;">
                <button type="button" class="synkra-btn" style="background: var(--surface-secondary); border: 1px solid var(--border); padding: 0.6rem 1.25rem; border-radius: 8px; cursor: pointer; color: var(--text-primary); font-weight: 600; display: flex; align-items: center; transition: all 0.2s;" onclick="verifyAccount()" id="btnVerify">
                    <i class="fa-solid fa-shield-check" style="margin-right: 8px; color: var(--primary);"></i> Verify Account
                </button>
            </div>

            <div id="verificationResult" style="margin-bottom: 1.5rem; display: none; padding: 1rem 1.25rem; background: rgba(16, 185, 129, 0.1); border-left: 4px solid var(--success); border-radius: 8px;">
                <span style="display: block; font-size: 0.75rem; color: var(--success); text-transform: uppercase; font-weight: 700; margin-bottom: 0.25rem;">Verified Account Name</span>
                <strong id="verifiedAccountNameDisplay" style="color: var(--text-primary); font-size: 1.15rem;"></strong>
            </div>

            <div style="margin-bottom: 1rem; padding-top: 1rem; border-top: 1px solid var(--border);">
                <div style="display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 1rem;">
                    <label style="font-size: 0.85rem; font-weight: 500; color: var(--text-primary);">Currency</label>
                    <select name="currency" style="width: 100%; padding: 0.75rem 1rem; background: var(--surface); border: 1px solid var(--border); border-radius: 8px; color: var(--text-primary); outline: none; font-family: inherit;">
                        <option value="GHS" selected>GHS - Ghana Cedi</option>
                        <option value="USD">USD - US Dollar</option>
                        <option value="EUR">EUR - Euro</option>
                        <option value="GBP">GBP - British Pound</option>
                        <option value="NGN">NGN - Nigerian Naira</option>
                        <option value="ZAR">ZAR - South African Rand</option>
                        <option value="KES">KES - Kenyan Shilling</option>
                        <option value="RWF">RWF - Rwandan Franc</option>
                        <option value="UGX">UGX - Ugandan Shilling</option>
                        <option value="TZS">TZS - Tanzanian Shilling</option>
                        <option value="ZMW">ZMW - Zambian Kwacha</option>
                        <option value="XOF">XOF - CFA Franc BCEAO</option>
                        <option value="XAF">XAF - CFA Franc BEAC</option>
                        <option value="EGP">EGP - Egyptian Pound</option>
                        <option value="MAD">MAD - Moroccan Dirham</option>
                        <option value="CAD">CAD - Canadian Dollar</option>
                        <option value="AUD">AUD - Australian Dollar</option>
                        <option value="INR">INR - Indian Rupee</option>
                        <option value="JPY">JPY - Japanese Yen</option>
                        <option value="CNY">CNY - Chinese Yuan</option>
                        <option value="BRL">BRL - Brazilian Real</option>
                        <option value="MXN">MXN - Mexican Peso</option>
                        <option value="AED">AED - UAE Dirham</option>
                        <option value="SAR">SAR - Saudi Riyal</option>
                        <option value="SGD">SGD - Singapore Dollar</option>
                    </select>
                </div>

            <div style="margin-bottom: 1rem;">
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label style="font-size: 0.85rem; font-weight: 500; color: var(--text-primary);">Settlement Schedule</label>
                    <select name="settlement_schedule" style="width: 100%; padding: 0.75rem 1rem; background: var(--surface); border: 1px solid var(--border); border-radius: 8px; color: var(--text-primary); outline: none; font-family: inherit;">
                        <option value="AUTO">Auto (Daily)</option>
                        <option value="MANUAL">Manual</option>
                    </select>
                </div>
            </div>
        </form>

        <x-slot:footer>
            <button type="button" class="synkra-btn synkra-btn-secondary" style="background: transparent; border: 1px solid var(--border); color: var(--text-secondary); cursor: pointer; padding: 0.5rem 1rem; border-radius: 8px;" onclick="closeSynkraModal('addSubaccountModal')">Cancel</button>
            <button type="button" id="btnSaveAccount" class="synkra-btn synkra-btn-primary" style="background: var(--primary); border: none; color: white; cursor: not-allowed; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; opacity: 0.5;" disabled onclick="document.getElementById('addSubaccountForm').submit()">Save Account</button>
        </x-slot:footer>
    </x-ui.modal>

    <script>
        function toggleAccountType(type) {
            document.getElementById('account_type_toggle').value = type;
            document.getElementById('verificationResult').style.display = 'none';
            document.getElementById('btnSaveAccount').disabled = true;
            document.getElementById('btnSaveAccount').style.opacity = '0.5';
            document.getElementById('btnSaveAccount').style.cursor = 'not-allowed';
            
            if(type === 'bank') {
                document.getElementById('bankSection').style.display = 'block';
                document.getElementById('momoSection').style.display = 'none';
                
                document.getElementById('btnTypeBank').style.background = 'var(--surface)';
                document.getElementById('btnTypeBank').style.boxShadow = '0 2px 8px rgba(0,0,0,0.06)';
                document.getElementById('btnTypeBank').style.color = 'var(--text-primary)';
                
                document.getElementById('btnTypeMomo').style.background = 'transparent';
                document.getElementById('btnTypeMomo').style.boxShadow = 'none';
                document.getElementById('btnTypeMomo').style.color = 'var(--text-secondary)';
            } else {
                document.getElementById('bankSection').style.display = 'none';
                document.getElementById('momoSection').style.display = 'block';
                
                document.getElementById('btnTypeMomo').style.background = 'var(--surface)';
                document.getElementById('btnTypeMomo').style.boxShadow = '0 2px 8px rgba(0,0,0,0.06)';
                document.getElementById('btnTypeMomo').style.color = 'var(--text-primary)';
                
                document.getElementById('btnTypeBank').style.background = 'transparent';
                document.getElementById('btnTypeBank').style.boxShadow = 'none';
                document.getElementById('btnTypeBank').style.color = 'var(--text-secondary)';
            }
        }

        async function verifyAccount() {
            const type = document.getElementById('account_type_toggle').value;
            let bankCode, accNum, bankName;
            
            if (type === 'bank') {
                bankCode = document.getElementById('input_bank_code').value;
                accNum = document.getElementById('input_bank_account').value;
                bankName = "Bank Account";
            } else {
                const select = document.getElementById('input_momo_network');
                bankCode = select.value;
                accNum = document.getElementById('input_momo_phone').value;
                bankName = select.options[select.selectedIndex].text;
            }

            if (!bankCode || !accNum) {
                alert('Please provide both the code/network and account/phone number.');
                return;
            }

            const btn = document.getElementById('btnVerify');
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="margin-right: 8px;"></i> Verifying...';
            btn.disabled = true;

            try {
                const res = await fetch("{{ route('settings.subaccounts.resolve') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ bank_code: bankCode, account_number: accNum })
                });
                const data = await res.json();
                
                if(data.success) {
                    document.getElementById('verifiedAccountNameDisplay').innerText = data.account_name;
                    
                    // Fill the hidden fields that will actually be submitted
                    document.getElementById('final_account_name').value = data.account_name;
                    document.getElementById('final_bank_code').value = bankCode;
                    document.getElementById('final_account_number').value = accNum;
                    document.getElementById('final_bank_name').value = bankName;
                    
                    document.getElementById('verificationResult').style.display = 'block';
                    
                    const saveBtn = document.getElementById('btnSaveAccount');
                    saveBtn.disabled = false;
                    saveBtn.style.opacity = '1';
                    saveBtn.style.cursor = 'pointer';
                } else {
                    alert("Verification failed: " + data.message);
                }
            } catch(e) {
                alert("Error connecting to Paystack verification service.");
            } finally {
                btn.innerHTML = '<i class="fa-solid fa-shield-check" style="margin-right: 8px; color: var(--primary);"></i> Verify Account';
                btn.disabled = false;
            }
        }
    </script>
</x-layouts.app>
