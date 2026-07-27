<x-layouts.app title="Stock Adjustment">
    <x-ui.grid>
        <div class="flowexa-dashboard-container" style="padding:2rem;max-width:720px;margin:0 auto;">
            <a href="{{ route('product_service.stocks.adjustments.index') }}" style="color:var(--text-secondary);text-decoration:none;font-size:.9rem;display:inline-flex;align-items:center;gap:.5rem;margin-bottom:1rem;"><i class="fa-solid fa-arrow-left"></i> Back to Adjustments</a>
            <h1 style="color:var(--headings);margin:0 0 1.5rem 0;">Create Stock Adjustment</h1>

            @if(session('success'))
                <x-ui.alert type="success" title="Success" :message="session('success')" style="margin-bottom:1.5rem;" />
            @endif
            @if($errors->any())
                <x-ui.alert type="danger" title="Error" :message="$errors->first()" style="margin-bottom:1.5rem;" />
            @endif

            <div class="flowexa-card" style="background:var(--surface);border-radius:16px;padding:1.5rem;border:1px solid var(--border);">
                <form action="{{ route('product_service.stocks.adjustment.store') }}" method="POST">
                    @csrf
                    @include('product_service.stocks.partials.stock-form-fields', ['products' => $products, 'locations' => $locations, 'reasons' => $reasons, 'formType' => 'adjustment'])
                    <div style="margin-top:1.5rem;text-align:right;">
                        <button type="submit" style="background:var(--primary);border:none;color:white;padding:.65rem 1.5rem;border-radius:8px;font-weight:600;cursor:pointer;">Save Adjustment</button>
                    </div>
                </form>
            </div>
        </div>

        <style>#createAdjustmentReasonModal-trigger-btn { display: none !important; }</style>

        <x-ui.modal id="createAdjustmentReasonModal" triggerId="createAdjustmentReasonModal-trigger-btn" title="Create Adjustment Reason">
            <form id="createAdjustmentReasonForm">
                @include('product_service.stocks.partials.adjustment-reason-form-fields')
                <div id="createAdjustmentReasonError" style="display:none;margin-top:1rem;padding:.75rem 1rem;background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.3);border-radius:8px;color:var(--danger);font-size:.85rem;"></div>
            </form>
            <x-slot:footer>
                <button type="button" style="background:transparent;border:1px solid var(--border);color:var(--text-secondary);cursor:pointer;padding:.5rem 1rem;border-radius:8px;" onclick="closeflowexaModal('createAdjustmentReasonModal')">Cancel</button>
                <button type="button" id="createAdjustmentReasonSubmitBtn" style="background:var(--primary);border:none;color:white;cursor:pointer;padding:.5rem 1rem;border-radius:8px;font-weight:600;" onclick="submitAdjustmentReasonModal()">Save Reason</button>
            </x-slot:footer>
        </x-ui.modal>

        <script>
            async function submitAdjustmentReasonModal() {
                const form = document.getElementById('createAdjustmentReasonForm');
                const errorEl = document.getElementById('createAdjustmentReasonError');
                const submitBtn = document.getElementById('createAdjustmentReasonSubmitBtn');
                if (!form) {
                    return;
                }

                const formData = new FormData(form);
                formData.append('_token', '{{ csrf_token() }}');

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Saving…';
                }
                if (errorEl) {
                    errorEl.style.display = 'none';
                }

                try {
                    const response = await fetch('{{ route('product_service.stocks.adjustment_reasons.store') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const data = await response.json();

                    if (!response.ok) {
                        const message = data.message || (data.errors ? Object.values(data.errors).flat().join(' ') : 'Failed to create reason.');
                        if (errorEl) {
                            errorEl.textContent = message;
                            errorEl.style.display = 'block';
                        }
                        return;
                    }

                    const select = document.getElementById('adjustmentReasonSelect');
                    const emptyState = document.getElementById('reasonEmptyState');

                    if (select) {
                        const option = document.createElement('option');
                        option.value = data.reason.id;
                        option.textContent = data.reason.reason_name + (data.reason.adjustment_type ? ' (' + data.reason.adjustment_type + ')' : '');
                        option.selected = true;
                        select.appendChild(option);
                        select.style.display = 'block';
                    }

                    if (emptyState) {
                        emptyState.style.display = 'none';
                    }

                    form.reset();
                    closeflowexaModal('createAdjustmentReasonModal');
                } catch (error) {
                    if (errorEl) {
                        errorEl.textContent = 'Network error. Please try again.';
                        errorEl.style.display = 'block';
                    }
                } finally {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Save Reason';
                    }
                }
            }
        </script>
    </x-ui.grid>
</x-layouts.app>
