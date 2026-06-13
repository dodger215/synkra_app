@php
    $prefix = $prefix ?? '';
@endphp

<div style="display:flex;flex-direction:column;gap:1rem;">
    <x-ui.input name="name" label="Location Name" placeholder="e.g. Main Warehouse" required />

    <div>
        <label style="font-size:.85rem;font-weight:500;color:var(--text-primary);display:block;margin-bottom:.4rem;">Location Type <span style="color:var(--danger);">*</span></label>
        <select name="location_type" required style="width:100%;padding:.75rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;font-family:inherit;outline:none;">
            <option value="">Select type</option>
            @foreach($locationTypeOptions as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label style="font-size:.85rem;font-weight:500;color:var(--text-primary);display:block;margin-bottom:.4rem;">Address</label>
        <textarea name="address" rows="2" style="width:100%;padding:.75rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;font-family:inherit;outline:none;" placeholder="Optional address"></textarea>
    </div>

    <div style="display:flex;flex-direction:column;gap:.75rem;margin-top:.25rem;">
        <div style="display:flex;align-items:center;gap:.5rem;">
            <input type="checkbox" name="is_default" id="{{ $prefix }}is_default" value="1" style="width:18px;height:18px;cursor:pointer;">
            <label for="{{ $prefix }}is_default" style="font-size:.9rem;color:var(--text-primary);cursor:pointer;">Set as default location</label>
        </div>
        <div style="display:flex;align-items:center;gap:.5rem;">
            <input type="checkbox" name="is_active" id="{{ $prefix }}is_active" value="1" checked style="width:18px;height:18px;cursor:pointer;">
            <label for="{{ $prefix }}is_active" style="font-size:.9rem;color:var(--text-primary);cursor:pointer;">Active location</label>
        </div>
    </div>
</div>
