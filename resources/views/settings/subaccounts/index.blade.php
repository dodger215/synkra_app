<x-layouts.app title="Billing & Subaccounts">
    <x-slot:head>
        <meta name="description" content="Manage your billing details and payment subaccounts.">
    </x-slot:head>

    <div class="synkra-dashboard-container" style="padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="color: var(--headings); margin: 0 0 0.5rem 0;">Payout Dashboard</h1>
                <p style="color: var(--text-secondary); margin: 0;">Manage your settlement account and view financial metrics.</p>
            </div>
            @if(!$subaccount)
            <button class="synkra-btn synkra-btn-primary" style="background: var(--primary); border: none; color: white; cursor: pointer; padding: 0.75rem 1.5rem; border-radius: 10px; font-weight: 600;" onclick="openSynkraModal('addSubaccountModal')">
                <i class="fa-solid fa-plus" style="margin-right: 8px;"></i> Add Subaccount
            </button>
            @endif
        </div>

        @if(session('status'))
            <x-ui.alert type="success" title="Success" message="{{ session('status') }}" style="margin-bottom: 2rem;" />
        @endif

        @if($errors->any())
            <x-ui.alert type="danger" title="Error" message="Please fix the errors in your form submission." style="margin-bottom: 2rem;" />
        @endif

        @if($subaccount)
            <!-- Financial Dashboard Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                
                <!-- Status Card -->
                <div class="synkra-card" style="padding: 1.5rem; background: var(--surface); border-radius: 20px; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.06); border: 1px solid var(--border); display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(16, 185, 129, 0.1); color: var(--success); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <div>
                        <p style="margin: 0; color: var(--text-secondary); font-size: 0.85rem; font-weight: 500; text-transform: uppercase;">Account Status</p>
                        <h3 style="margin: 0; color: var(--headings); display: flex; align-items: center; gap: 0.5rem;">
                            {{ $subaccount->is_active ? 'Active' : 'Inactive' }}
                            <x-ui.badge type="{{ $subaccount->is_active ? 'success' : 'warning' }}">
                                <i class="fa-solid fa-circle-check"></i> Verified
                            </x-ui.badge>
                        </h3>
                    </div>
                </div>

                <!-- Transactions -->
                <div class="synkra-card" style="padding: 1.5rem; background: var(--surface); border-radius: 20px; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.06); border: 1px solid var(--border); display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(59, 130, 246, 0.1); color: #3b82f6; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                    </div>
                    <div>
                        <p style="margin: 0; color: var(--text-secondary); font-size: 0.85rem; font-weight: 500; text-transform: uppercase;">Total Transactions</p>
                        <h3 style="margin: 0; color: var(--headings);">{{ number_format($transactionsCount) }}</h3>
                    </div>
                </div>

                <!-- Revenue -->
                <div class="synkra-card" style="padding: 1.5rem; background: var(--surface); border-radius: 20px; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.06); border: 1px solid var(--border); display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(245, 158, 11, 0.1); color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <div>
                        <p style="margin: 0; color: var(--text-secondary); font-size: 0.85rem; font-weight: 500; text-transform: uppercase;">Total Revenue</p>
                        <h3 style="margin: 0; color: var(--headings);">{{ $subaccount->currency }} {{ number_format($totalRevenue, 2) }}</h3>
                    </div>
                </div>

                <!-- Payouts -->
                <div class="synkra-card" style="padding: 1.5rem; background: var(--surface); border-radius: 20px; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.06); border: 1px solid var(--border); display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(139, 92, 246, 0.1); color: #8b5cf6; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <div>
                        <p style="margin: 0; color: var(--text-secondary); font-size: 0.85rem; font-weight: 500; text-transform: uppercase;">Total Payouts</p>
                        <h3 style="margin: 0; color: var(--headings);">{{ $subaccount->currency }} {{ number_format($totalPayouts, 2) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Account Details Card -->
            <div class="synkra-card" style="padding: 2rem; background: var(--surface); border-radius: 20px; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.06); border: 1px solid var(--border);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h3 style="margin: 0; color: var(--headings); font-size: 1.2rem;">Settlement Account Details</h3>
                    <div style="display: flex; gap: 0.5rem;">
                        <button type="button" style="background: rgba(59, 130, 246, 0.1); border: none; color: #3b82f6; padding: 0.6rem 1.2rem; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.2s;" onclick="openSynkraModal('editSubaccountModal')">
                            <i class="fa-solid fa-pen" style="margin-right: 5px;"></i> Edit
                        </button>
                        <form action="{{ route('settings.subaccounts.destroy', $subaccount->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this settlement account? All future payouts will be halted.');">
                            @csrf @method('DELETE')
                            <button type="submit" style="background: rgba(239, 68, 68, 0.1); border: none; color: var(--danger); padding: 0.6rem 1.2rem; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.2s;">
                                <i class="fa-solid fa-trash" style="margin-right: 5px;"></i> Delete Account
                            </button>
                        </form>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; padding: 1.5rem; background: var(--surface-secondary); border-radius: 12px;">
                    <div>
                        <span style="display: block; font-size: 0.8rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Bank / Provider</span>
                        <strong style="color: var(--text-primary); font-size: 1.1rem;">{{ $subaccount->bank_name ?: 'Bank Account' }} ({{ $subaccount->bank_code }})</strong>
                    </div>
                    <div>
                        <span style="display: block; font-size: 0.8rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Account Number</span>
                        <strong style="color: var(--text-primary); font-size: 1.1rem; font-family: monospace; letter-spacing: 1px;">•••• •••• •••• {{ substr($subaccount->account_number, -4) }}</strong>
                    </div>
                    <div>
                        <span style="display: block; font-size: 0.8rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Account Name</span>
                        <strong style="color: var(--text-primary); font-size: 1.1rem;">{{ $subaccount->account_name }}</strong>
                    </div>
                    <div>
                        <span style="display: block; font-size: 0.8rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Settlement Schedule</span>
                        <strong style="color: var(--text-primary); font-size: 1.1rem;">{{ ucfirst(strtolower($subaccount->settlement_schedule)) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Edit Subaccount Modal -->
            <style>#editSubaccountModal-trigger-btn { display: none !important; }</style>
            <x-ui.modal id="editSubaccountModal" triggerId="editSubaccountModal-trigger-btn" title="Edit Settlement Account">
                <form id="editSubaccountForm" action="{{ route('settings.subaccounts.update', $subaccount->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 0; margin-bottom: 1.5rem;">Update your banking details for receiving payouts.</p>

                    <!-- Active Toggle -->
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; background: var(--surface-secondary); padding: 1rem; border-radius: 12px;">
                        <div>
                            <strong style="color: var(--text-primary); font-size: 0.95rem; display: block;">Account Status</strong>
                            <span style="color: var(--text-secondary); font-size: 0.8rem;">Enable or disable payouts to this account.</span>
                        </div>
                        <label style="position: relative; display: inline-block; width: 50px; height: 26px;">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" style="opacity: 0; width: 0; height: 0;" {{ $subaccount->is_active ? 'checked' : '' }} onchange="this.nextElementSibling.style.background = this.checked ? 'var(--success)' : 'var(--border)'">
                            <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: {{ $subaccount->is_active ? 'var(--success)' : 'var(--border)' }}; transition: .4s; border-radius: 34px;">
                                <span style="position: absolute; content: ''; height: 18px; width: 18px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; transform: translateX({{ $subaccount->is_active ? '24px' : '0' }});"></span>
                            </span>
                            <script>
                                document.querySelector('input[name="is_active"][type="checkbox"]').addEventListener('change', function() {
                                    const slider = this.nextElementSibling.querySelector('span');
                                    slider.style.transform = this.checked ? 'translateX(24px)' : 'translateX(0)';
                                });
                            </script>
                        </label>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; background: var(--surface-secondary); padding: 0.35rem; border-radius: 12px;">
                        <button type="button" id="editBtnTypeBank" style="flex: 1; padding: 0.6rem; border-radius: 8px; border: none; background: var(--surface); box-shadow: 0 2px 8px rgba(0,0,0,0.06); color: var(--text-primary); font-weight: 600; cursor: pointer; transition: all 0.2s;" onclick="toggleEditAccountType('bank')">Bank Account</button>
                        <button type="button" id="editBtnTypeMomo" style="flex: 1; padding: 0.6rem; border-radius: 8px; border: none; background: transparent; color: var(--text-secondary); font-weight: 600; cursor: pointer; transition: all 0.2s;" onclick="toggleEditAccountType('momo')">Mobile Money</button>
                    </div>

                    <input type="hidden" id="edit_account_type_toggle" value="bank">
                    
                    <input type="hidden" name="bank_code" id="edit_final_bank_code" value="{{ $subaccount->bank_code }}">
                    <input type="hidden" name="bank_name" id="edit_final_bank_name" value="{{ $subaccount->bank_name }}">
                    <input type="hidden" name="account_number" id="edit_final_account_number" value="{{ $subaccount->account_number }}">
                    <input type="hidden" name="account_name" id="edit_final_account_name" value="{{ $subaccount->account_name }}">

                    <div id="editBankSection">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <x-ui.input name="edit_input_bank_code" id="edit_input_bank_code" label="Bank / Routing Code" value="{{ $subaccount->bank_code }}" />
                            <x-ui.input name="edit_input_bank_account" id="edit_input_bank_account" label="Account Number" value="{{ $subaccount->account_number }}" />
                        </div>
                    </div>

                    <div id="editMomoSection" style="display: none;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <label style="font-size: 0.85rem; font-weight: 500; color: var(--text-primary);">Mobile Network</label>
                                <select id="edit_input_momo_network" style="width: 100%; padding: 0.75rem 1rem; background: var(--surface); border: 1px solid var(--border); border-radius: 8px; color: var(--text-primary); outline: none; font-family: inherit;">
                                    <option value="MTN">MTN Mobile Money</option>
                                    <option value="VOD">Telcel Cash</option>
                                    <option value="ATL">AirtelTigo Money</option>
                                </select>
                            </div>
                            <x-ui.input name="edit_input_momo_phone" id="edit_input_momo_phone" label="Phone Number" />
                        </div>
                    </div>

                    <div style="margin-bottom: 1.5rem; display: flex; justify-content: flex-end;">
                        <button type="button" class="synkra-btn" style="background: var(--surface-secondary); border: 1px solid var(--border); padding: 0.6rem 1.25rem; border-radius: 8px; cursor: pointer; color: var(--text-primary); font-weight: 600; display: flex; align-items: center; transition: all 0.2s;" onclick="verifyEditAccount()" id="btnEditVerify">
                            <i class="fa-solid fa-shield-check" style="margin-right: 8px; color: var(--primary);"></i> Re-Verify Account
                        </button>
                    </div>

                    <div id="editVerificationResult" style="margin-bottom: 1.5rem; display: block; padding: 1rem 1.25rem; background: rgba(16, 185, 129, 0.1); border-left: 4px solid var(--success); border-radius: 8px;">
                        <span style="display: block; font-size: 0.75rem; color: var(--success); text-transform: uppercase; font-weight: 700; margin-bottom: 0.25rem;">Verified Account Name</span>
                        <strong id="editVerifiedAccountNameDisplay" style="color: var(--text-primary); font-size: 1.15rem;">{{ $subaccount->account_name }}</strong>
                    </div>

                    <div style="margin-bottom: 1rem; padding-top: 1rem; border-top: 1px solid var(--border);">
                        <div style="display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 1rem;">
                            <label style="font-size: 0.85rem; font-weight: 500; color: var(--text-primary);">Currency</label>
                            <select name="currency" style="width: 100%; padding: 0.75rem 1rem; background: var(--surface); border: 1px solid var(--border); border-radius: 8px; color: var(--text-primary); outline: none; font-family: inherit;">
                                <option value="GHS" {{ $subaccount->currency == 'GHS' ? 'selected' : '' }}>GHS - Ghana Cedi</option>
                                <option value="USD" {{ $subaccount->currency == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                <option value="EUR" {{ $subaccount->currency == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                <option value="GBP" {{ $subaccount->currency == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                <option value="NGN" {{ $subaccount->currency == 'NGN' ? 'selected' : '' }}>NGN - Nigerian Naira</option>
                            </select>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                            <label style="font-size: 0.85rem; font-weight: 500; color: var(--text-primary);">Settlement Schedule</label>
                            <select name="settlement_schedule" style="width: 100%; padding: 0.75rem 1rem; background: var(--surface); border: 1px solid var(--border); border-radius: 8px; color: var(--text-primary); outline: none; font-family: inherit;">
                                <option value="AUTO" {{ $subaccount->settlement_schedule == 'AUTO' ? 'selected' : '' }}>Auto (Daily)</option>
                                <option value="MANUAL" {{ $subaccount->settlement_schedule == 'MANUAL' ? 'selected' : '' }}>Manual</option>
                            </select>
                        </div>
                    </div>
                </form>

                <x-slot:footer>
                    <button type="button" class="synkra-btn synkra-btn-secondary" style="background: transparent; border: 1px solid var(--border); color: var(--text-secondary); cursor: pointer; padding: 0.5rem 1rem; border-radius: 8px;" onclick="closeSynkraModal('editSubaccountModal')">Cancel</button>
                    <button type="button" id="btnEditSaveAccount" class="synkra-btn synkra-btn-primary" style="background: var(--primary); border: none; color: white; cursor: pointer; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600;" onclick="document.getElementById('editSubaccountForm').submit()">Save Changes</button>
                </x-slot:footer>
            </x-ui.modal>
        @else
            <!-- Empty State -->
            <div style="text-align: center; padding: 5rem 2rem; background: var(--surface); border-radius: 20px; border: 2px dashed var(--border);">
                <div style="width: 80px; height: 80px; background: var(--surface-secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
                    <i class="fa-solid fa-wallet" style="font-size: 2.5rem; color: var(--primary);"></i>
                </div>
                <h3 style="margin: 0 0 0.5rem 0; color: var(--text-primary); font-size: 1.25rem;">No Settlement Account Configured</h3>
                <p style="margin: 0 0 2rem 0; color: var(--text-secondary); max-width: 400px; margin-left: auto; margin-right: auto;">You need to link a bank account or mobile money wallet to receive your payouts from sales.</p>
                <button class="synkra-btn synkra-btn-primary" style="background: var(--primary); border: none; color: white; cursor: pointer; padding: 0.75rem 2rem; border-radius: 10px; font-weight: 600;" onclick="openSynkraModal('addSubaccountModal')">
                    <i class="fa-solid fa-link" style="margin-right: 8px;"></i> Link Account Now
                </button>
            </div>
        @endif
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

        // Edit Modal JS
        function toggleEditAccountType(type) {
            document.getElementById('edit_account_type_toggle').value = type;
            document.getElementById('editVerificationResult').style.display = 'none';
            document.getElementById('btnEditSaveAccount').disabled = true;
            document.getElementById('btnEditSaveAccount').style.opacity = '0.5';
            document.getElementById('btnEditSaveAccount').style.cursor = 'not-allowed';
            
            if(type === 'bank') {
                document.getElementById('editBankSection').style.display = 'block';
                document.getElementById('editMomoSection').style.display = 'none';
                
                document.getElementById('editBtnTypeBank').style.background = 'var(--surface)';
                document.getElementById('editBtnTypeBank').style.boxShadow = '0 2px 8px rgba(0,0,0,0.06)';
                document.getElementById('editBtnTypeBank').style.color = 'var(--text-primary)';
                
                document.getElementById('editBtnTypeMomo').style.background = 'transparent';
                document.getElementById('editBtnTypeMomo').style.boxShadow = 'none';
                document.getElementById('editBtnTypeMomo').style.color = 'var(--text-secondary)';
            } else {
                document.getElementById('editBankSection').style.display = 'none';
                document.getElementById('editMomoSection').style.display = 'block';
                
                document.getElementById('editBtnTypeMomo').style.background = 'var(--surface)';
                document.getElementById('editBtnTypeMomo').style.boxShadow = '0 2px 8px rgba(0,0,0,0.06)';
                document.getElementById('editBtnTypeMomo').style.color = 'var(--text-primary)';
                
                document.getElementById('editBtnTypeBank').style.background = 'transparent';
                document.getElementById('editBtnTypeBank').style.boxShadow = 'none';
                document.getElementById('editBtnTypeBank').style.color = 'var(--text-secondary)';
            }
        }

        async function verifyEditAccount() {
            const type = document.getElementById('edit_account_type_toggle').value;
            let bankCode, accNum, bankName;
            
            if (type === 'bank') {
                bankCode = document.getElementById('edit_input_bank_code').value;
                accNum = document.getElementById('edit_input_bank_account').value;
                bankName = "Bank Account";
            } else {
                const select = document.getElementById('edit_input_momo_network');
                bankCode = select.value;
                accNum = document.getElementById('edit_input_momo_phone').value;
                bankName = select.options[select.selectedIndex].text;
            }

            if (!bankCode || !accNum) {
                alert('Please provide both the code/network and account/phone number.');
                return;
            }

            const btn = document.getElementById('btnEditVerify');
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
                    document.getElementById('editVerifiedAccountNameDisplay').innerText = data.account_name;
                    
                    document.getElementById('edit_final_account_name').value = data.account_name;
                    document.getElementById('edit_final_bank_code').value = bankCode;
                    document.getElementById('edit_final_account_number').value = accNum;
                    document.getElementById('edit_final_bank_name').value = bankName;
                    
                    document.getElementById('editVerificationResult').style.display = 'block';
                    
                    const saveBtn = document.getElementById('btnEditSaveAccount');
                    saveBtn.disabled = false;
                    saveBtn.style.opacity = '1';
                    saveBtn.style.cursor = 'pointer';
                } else {
                    alert("Verification failed: " + data.message);
                }
            } catch(e) {
                alert("Error connecting to Paystack verification service.");
            } finally {
                btn.innerHTML = '<i class="fa-solid fa-shield-check" style="margin-right: 8px; color: var(--primary);"></i> Re-Verify Account';
                btn.disabled = false;
            }
        }
    </script>
</x-layouts.app>
