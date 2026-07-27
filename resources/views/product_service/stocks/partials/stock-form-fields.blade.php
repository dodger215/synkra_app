@php
    $products = $products ?? collect();
    $locations = $locations ?? collect();
    $reasons = $reasons ?? collect();
    $formType = $formType ?? 'receive';
@endphp

<div style="display:flex;flex-direction:column;gap:1rem;">
    <div>
        <label style="font-size:.85rem;font-weight:600;color:var(--headings);display:block;margin-bottom:.4rem;">Product <span style="color:var(--danger)">*</span></label>
        <select name="product_id" required style="width:100%;padding:.7rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;">
            <option value="">Select product</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>{{ $product->name }} ({{ $product->sku }})</option>
            @endforeach
        </select>
    </div>

    @if($formType === 'transfer')
        <div>
            <label style="font-size:.85rem;font-weight:600;color:var(--headings);display:block;margin-bottom:.4rem;">From Location <span style="color:var(--danger)">*</span></label>
            <select name="from_location_id" required style="width:100%;padding:.7rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;">
                <option value="">Select location</option>
                @foreach($locations as $location)
                    <option value="{{ $location->id }}" @selected(old('from_location_id') == $location->id)>{{ $location->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="font-size:.85rem;font-weight:600;color:var(--headings);display:block;margin-bottom:.4rem;">To Location <span style="color:var(--danger)">*</span></label>
            <select name="to_location_id" required style="width:100%;padding:.7rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;">
                <option value="">Select location</option>
                @foreach($locations as $location)
                    <option value="{{ $location->id }}" @selected(old('to_location_id') == $location->id)>{{ $location->name }}</option>
                @endforeach
            </select>
        </div>
    @else
        <div>
            <label style="font-size:.85rem;font-weight:600;color:var(--headings);display:block;margin-bottom:.4rem;">Location <span style="color:var(--danger)">*</span></label>
            <select name="location_id" required style="width:100%;padding:.7rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;">
                <option value="">Select location</option>
                @foreach($locations as $location)
                    <option value="{{ $location->id }}" @selected(old('location_id') == $location->id)>{{ $location->name }}</option>
                @endforeach
            </select>
        </div>
    @endif

    @if($formType === 'adjustment')
        <div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.4rem;">
                <label style="font-size:.85rem;font-weight:600;color:var(--headings);">Reason</label>
            </div>

            @if($reasons->isEmpty())
                <div id="reasonEmptyState" style="padding:.9rem 1rem;background:rgba(245,158,11,.06);border:1px solid rgba(245,158,11,.3);border-radius:8px;font-size:.85rem;color:var(--text-secondary);margin-bottom:.75rem;">
                    <i class="fa-solid fa-circle-exclamation" style="color:#f59e0b;margin-right:.4rem;"></i>
                    No adjustment reasons yet. Create one below.
                </div>
            @endif

            <select id="adjustmentReasonSelect" name="reason_id" style="width:100%;padding:.7rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;margin-bottom:.75rem;{{ $reasons->isEmpty() ? 'display:none;' : '' }}">
                <option value="">Select reason</option>
                @foreach($reasons as $reason)
                    <option value="{{ $reason->id }}" @selected(old('reason_id') == $reason->id)>{{ $reason->reason_name }}@if($reason->adjustment_type) ({{ $reason->adjustment_type }})@endif</option>
                @endforeach
            </select>

            <button type="button" onclick="openflowexaModal('createAdjustmentReasonModal')"
                    style="width:100%;padding:.55rem 1rem;background:var(--surface-secondary);border:1px dashed var(--border);border-radius:8px;color:var(--primary);font-size:.85rem;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.5rem;">
                <i class="fa-solid fa-plus"></i> Create New Reason
            </button>
        </div>
        <div>
            <label style="font-size:.85rem;font-weight:600;color:var(--headings);display:block;margin-bottom:.4rem;">Adjustment Type <span style="color:var(--danger)">*</span></label>
            <select name="adjustment_type" required style="width:100%;padding:.7rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;">
                <option value="increase" @selected(old('adjustment_type') === 'increase')>Increase</option>
                <option value="decrease" @selected(old('adjustment_type') === 'decrease')>Decrease</option>
            </select>
        </div>
        <div>
            <label style="font-size:.85rem;font-weight:600;color:var(--headings);display:block;margin-bottom:.4rem;">Quantity Change <span style="color:var(--danger)">*</span></label>
            <input type="number" name="quantity_change" min="0.01" step="0.01" value="{{ old('quantity_change') }}" required style="width:100%;padding:.7rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;">
        </div>
    @elseif($formType === 'count')
        <div>
            <label style="font-size:.85rem;font-weight:600;color:var(--headings);display:block;margin-bottom:.4rem;">Counted Quantity <span style="color:var(--danger)">*</span></label>
            <input type="number" name="counted_quantity" min="0" step="0.01" value="{{ old('counted_quantity') }}" required style="width:100%;padding:.7rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;">
        </div>
    @elseif($formType === 'damage')
        <div>
            <label style="font-size:.85rem;font-weight:600;color:var(--headings);display:block;margin-bottom:.4rem;">Quantity <span style="color:var(--danger)">*</span></label>
            <input type="number" name="quantity" min="0.01" step="0.01" value="{{ old('quantity') }}" required style="width:100%;padding:.7rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;">
        </div>
        <div>
            <label style="font-size:.85rem;font-weight:600;color:var(--headings);display:block;margin-bottom:.4rem;">Damage Type</label>
            <input type="text" name="damage_type" value="{{ old('damage_type') }}" style="width:100%;padding:.7rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;">
        </div>
        <div>
            <label style="font-size:.85rem;font-weight:600;color:var(--headings);display:block;margin-bottom:.4rem;">Severity</label>
            <input type="text" name="severity" value="{{ old('severity') }}" style="width:100%;padding:.7rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;">
        </div>
    @elseif($formType === 'return')
        <div>
            <label style="font-size:.85rem;font-weight:600;color:var(--headings);display:block;margin-bottom:.4rem;">Quantity <span style="color:var(--danger)">*</span></label>
            <input type="number" name="quantity" min="0.01" step="0.01" value="{{ old('quantity') }}" required style="width:100%;padding:.7rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;">
        </div>
        <div>
            <label style="font-size:.85rem;font-weight:600;color:var(--headings);display:block;margin-bottom:.4rem;">Return Reason</label>
            <input type="text" name="return_reason" value="{{ old('return_reason') }}" style="width:100%;padding:.7rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;">
        </div>
        <div>
            <label style="font-size:.85rem;font-weight:600;color:var(--headings);display:block;margin-bottom:.4rem;">Condition</label>
            <input type="text" name="condition" value="{{ old('condition') }}" style="width:100%;padding:.7rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;">
        </div>
    @else
        <div>
            <label style="font-size:.85rem;font-weight:600;color:var(--headings);display:block;margin-bottom:.4rem;">Quantity <span style="color:var(--danger)">*</span></label>
            <input type="number" name="quantity" min="0.01" step="0.01" value="{{ old('quantity') }}" required style="width:100%;padding:.7rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;">
        </div>
    @endif

    <div>
        <label style="font-size:.85rem;font-weight:600;color:var(--headings);display:block;margin-bottom:.4rem;">Notes</label>
        <textarea name="notes" rows="3" style="width:100%;padding:.7rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;font-family:inherit;">{{ old('notes') }}</textarea>
    </div>
</div>
