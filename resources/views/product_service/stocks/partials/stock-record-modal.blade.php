<style>#stockRecordModal-trigger-btn { display: none !important; }</style>

<x-ui.modal title="Record Details" id="stockRecordModal" triggerId="stockRecordModal-trigger-btn">
    <div id="stockRecordModalBody"></div>
    <x-slot:footer>
        <button type="button" style="background:transparent;border:1px solid var(--border);color:var(--text-secondary);cursor:pointer;padding:.5rem 1rem;border-radius:8px;" onclick="closeflowexaModal('stockRecordModal')">Close</button>
        <a id="stockRecordEditLink" href="#" style="background:var(--primary);border:none;color:white;cursor:pointer;padding:.5rem 1rem;border-radius:8px;font-weight:600;text-decoration:none;display:none;">
            <i class="fa-solid fa-pen-to-square"></i> Edit / Create New
        </a>
    </x-slot:footer>
</x-ui.modal>

<script>
if (typeof openStockRecordModal !== 'function') {
    function openStockRecordModal(recordId, recordsMap, editUrl = null) {
        const record = recordsMap[recordId];
        const body = document.getElementById('stockRecordModalBody');
        const editLink = document.getElementById('stockRecordEditLink');

        if (!record || !body) {
            return;
        }

        body.innerHTML = `
            <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:.75rem;">
                ${Object.entries(record).map(([key, value]) => `
                    <div style="padding:.75rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;">
                        <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.4px;color:var(--text-secondary);margin-bottom:.25rem;">
                            ${String(key).replace(/_/g, ' ')}
                        </div>
                        <div style="font-size:.9rem;font-weight:600;color:var(--text-primary);word-break:break-word;">
                            ${value === null || value === '' ? '—' : String(value).replace(/</g, '&lt;')}
                        </div>
                    </div>
                `).join('')}
            </div>
        `;

        if (editLink) {
            if (editUrl) {
                editLink.href = editUrl;
                editLink.style.display = 'inline-flex';
            } else {
                editLink.style.display = 'none';
            }
        }

        openflowexaModal('stockRecordModal');
    }
}
</script>
