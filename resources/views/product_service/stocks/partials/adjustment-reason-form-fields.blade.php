<div style="display:flex;flex-direction:column;gap:1rem;">
    <x-ui.input name="reason_name" label="Reason Name" placeholder="e.g. Cycle count correction" required />

    <x-ui.input name="reason_code" label="Reason Code" placeholder="e.g. CC-001" />

    <div>
        <label style="font-size:.85rem;font-weight:600;color:var(--headings);display:block;margin-bottom:.4rem;">Adjustment Type</label>
        <select name="adjustment_type" style="width:100%;padding:.7rem 1rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:.95rem;">
            <option value="">Any (increase or decrease)</option>
            <option value="increase">Increase</option>
            <option value="decrease">Decrease</option>
        </select>
    </div>

    <x-ui.input name="category" label="Category" placeholder="e.g. correction, shrinkage" />
</div>
