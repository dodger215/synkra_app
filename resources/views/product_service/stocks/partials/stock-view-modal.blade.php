@php
    $stockViewTabs = [
        ['id' => 'overview', 'label' => 'Overview', 'icon' => 'fa-solid fa-box'],
        ['id' => 'movements', 'label' => 'Movements', 'icon' => 'fa-solid fa-arrows-rotate'],
        ['id' => 'adjustments', 'label' => 'Adjustments', 'icon' => 'fa-solid fa-sliders'],
        ['id' => 'transfers', 'label' => 'Transfers', 'icon' => 'fa-solid fa-truck'],
        ['id' => 'damage', 'label' => 'Damage', 'icon' => 'fa-solid fa-triangle-exclamation'],
        ['id' => 'counts', 'label' => 'Counts', 'icon' => 'fa-solid fa-clipboard-check'],
        ['id' => 'returns', 'label' => 'Returns', 'icon' => 'fa-solid fa-rotate-left'],
        ['id' => 'location', 'label' => 'Location & Bins', 'icon' => 'fa-solid fa-warehouse'],
    ];

    $stockTabRoutes = [
        'movements' => ['list' => route('product_service.stocks.movements'), 'create' => route('product_service.stocks.receive.create'), 'createLabel' => 'Receive Stock'],
        'adjustments' => ['list' => route('product_service.stocks.adjustments.index'), 'create' => route('product_service.stocks.adjustment.create'), 'createLabel' => 'Create Adjustment'],
        'transfers' => ['list' => route('product_service.stocks.transfers.index'), 'create' => route('product_service.stocks.transfer.create'), 'createLabel' => 'Create Transfer'],
        'damage' => ['list' => route('product_service.stocks.damages.index'), 'create' => route('product_service.stocks.damage.create'), 'createLabel' => 'Report Damage'],
        'counts' => ['list' => route('product_service.stocks.counts.index'), 'create' => route('product_service.stocks.count.create'), 'createLabel' => 'Record Count'],
        'returns' => ['list' => route('product_service.stocks.returns.index'), 'create' => route('product_service.stocks.return.create'), 'createLabel' => 'Record Return'],
        'location' => ['list' => route('product_service.stocks.locations.index'), 'create' => route('product_service.stocks.locations.index'), 'createLabel' => 'Manage Locations'],
        'bins' => ['list' => route('product_service.stocks.bins.index'), 'create' => route('product_service.stocks.bins.index'), 'createLabel' => 'Manage Bins'],
    ];
@endphp

<style>
    #stockViewModal-trigger-btn {
        display: none !important;
    }

    #stockViewModal .flowexa-modal-container {
        max-width: 920px;
    }

    #stockViewModal .flowexa-modal-body {
        max-height: 72vh;
        padding-top: 0;
    }

    .stock-view-table {
        width: 100%;
        border-collapse: collapse;
        font-size: .85rem;
    }

    .stock-view-table th,
    .stock-view-table td {
        padding: .65rem .75rem;
        border-bottom: 1px solid var(--border);
        text-align: left;
        vertical-align: top;
    }

    .stock-view-table th {
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: var(--text-secondary);
        background: var(--surface-secondary);
    }

    .stock-view-empty {
        padding: 2rem 1rem;
        text-align: center;
        color: var(--text-secondary);
        font-size: .9rem;
    }

    .stock-view-empty-actions {
        display: flex;
        gap: .75rem;
        justify-content: center;
        margin-top: 1rem;
        flex-wrap: wrap;
    }

    .stock-view-empty-actions a {
        padding: .55rem 1rem;
        border-radius: 8px;
        font-size: .85rem;
        font-weight: 600;
        text-decoration: none;
    }

    .stock-view-empty-actions .list-link {
        background: var(--surface-secondary);
        border: 1px solid var(--border);
        color: var(--text-primary);
    }

    .stock-view-empty-actions .create-link {
        background: var(--primary);
        border: none;
        color: white;
    }
</style>

<x-ui.modal title="Stock Details" id="stockViewModal" triggerId="stockViewModal-trigger-btn">
    <div id="stockViewModalHeader" style="margin-bottom:1rem;"></div>
    <x-ui.tabs :tabs="$stockViewTabs" activeTab="overview" id="stockViewTabs" />
    <div id="stockViewTabContent" style="padding-top:1.25rem;"></div>
    <x-slot:footer>
        <button type="button" style="background:transparent;border:1px solid var(--border);color:var(--text-secondary);cursor:pointer;padding:.5rem 1rem;border-radius:8px;" onclick="closeflowexaModal('stockViewModal')">Close</button>
    </x-slot:footer>
</x-ui.modal>

<script>
    const stockDetailsByProduct = @json($stockDetailsByProduct ?? []);
    const stockTabRoutes = @json($stockTabRoutes);
    let currentStockViewProductId = null;

    function stockEscapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function stockFormatMoney(value) {
        const amount = Number(value || 0);
        return '$' + amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function stockEmptyState(message, tabKey = null) {
        let actions = '';
        if (tabKey && stockTabRoutes[tabKey]) {
            const routes = stockTabRoutes[tabKey];
            actions = `
                <div class="stock-view-empty-actions">
                    <a href="${routes.list}" class="list-link">View All</a>
                    <a href="${routes.create}" class="create-link">${stockEscapeHtml(routes.createLabel)}</a>
                </div>
            `;
        }

        return `<div class="stock-view-empty"><i class="fa-solid fa-inbox" style="display:block;font-size:1.5rem;margin-bottom:.5rem;opacity:.45;"></i>${stockEscapeHtml(message)}${actions}</div>`;
    }

    function stockRenderTable(headers, rows, emptyMessage = 'No records found.', tabKey = null) {
        if (!rows.length) {
            return stockEmptyState(emptyMessage, tabKey);
        }

        return `
            <div style="overflow-x:auto;border:1px solid var(--border);border-radius:10px;">
                <table class="stock-view-table">
                    <thead><tr>${headers.map((header) => `<th>${stockEscapeHtml(header)}</th>`).join('')}</tr></thead>
                    <tbody>${rows.map((row) => `<tr>${row.map((cell) => `<td>${cell}</td>`).join('')}</tr>`).join('')}</tbody>
                </table>
            </div>
        `;
    }

    function stockRenderOverview(details) {
        const product = details.product;
        const image = product.images?.[0]
            ? `<img src="${stockEscapeHtml(product.images[0])}" alt="${stockEscapeHtml(product.name)}" style="width:100%;height:100%;object-fit:cover;">`
            : `<i class="fa-solid fa-image" style="font-size:2rem;opacity:.35;"></i>`;

        const balanceCards = (details.balances || []).map((balance) => `
            <div style="padding:.85rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;">
                <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.4px;color:var(--text-secondary);margin-bottom:.35rem;">${stockEscapeHtml(balance.location || 'Location')}</div>
                <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:.5rem;font-size:.82rem;">
                    <div><span style="color:var(--text-secondary);">On Hand</span><br><strong>${stockEscapeHtml(balance.quantity_on_hand)}</strong></div>
                    <div><span style="color:var(--text-secondary);">Available</span><br><strong>${stockEscapeHtml(balance.quantity_available)}</strong></div>
                    <div><span style="color:var(--text-secondary);">Reserved</span><br><strong>${stockEscapeHtml(balance.quantity_reserved)}</strong></div>
                    <div><span style="color:var(--text-secondary);">Status</span><br><strong style="text-transform:capitalize;">${stockEscapeHtml(balance.reorder_status)}</strong></div>
                </div>
            </div>
        `).join('');

        return `
            <div style="display:grid;grid-template-columns:180px 1fr;gap:1.25rem;">
                <div style="border-radius:10px;overflow:hidden;border:1px solid var(--border);aspect-ratio:1;background:var(--surface-secondary);display:flex;align-items:center;justify-content:center;">${image}</div>
                <div>
                    <h3 style="margin:0 0 .35rem 0;color:var(--headings);">${stockEscapeHtml(product.name)}</h3>
                    <p style="margin:0 0 1rem 0;color:var(--text-secondary);font-size:.85rem;">
                        SKU: ${stockEscapeHtml(product.sku)}
                        ${product.barcode ? ` · Barcode: ${stockEscapeHtml(product.barcode)}` : ''}
                        ${product.category ? ` · ${stockEscapeHtml(product.category)}` : ''}
                    </p>
                    <p style="margin:0 0 1rem 0;color:var(--text-primary);font-size:.9rem;line-height:1.55;">${stockEscapeHtml(product.description || 'No description provided.')}</p>
                    <div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:.75rem;margin-bottom:1rem;">
                        <div style="padding:.75rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;">
                            <div style="font-size:.72rem;color:var(--text-secondary);margin-bottom:.2rem;">Selling Price</div>
                            <div style="font-weight:600;">${stockFormatMoney(product.unit_price)}</div>
                        </div>
                        <div style="padding:.75rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;">
                            <div style="font-size:.72rem;color:var(--text-secondary);margin-bottom:.2rem;">Cost Price</div>
                            <div style="font-weight:600;">${stockFormatMoney(product.cost_price)}</div>
                        </div>
                        <div style="padding:.75rem;background:var(--surface-secondary);border:1px solid var(--border);border-radius:8px;">
                            <div style="font-size:.72rem;color:var(--text-secondary);margin-bottom:.2rem;">Reorder Point</div>
                            <div style="font-weight:600;">${stockEscapeHtml(product.reorder_point ?? '—')}</div>
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:.75rem;">
                        ${balanceCards || stockEmptyState('No stock balances found.', 'location')}
                    </div>
                </div>
            </div>
        `;
    }

    function stockRenderMovements(details) {
        const rows = (details.movements || []).map((movement) => [
            stockEscapeHtml(movement.date),
            stockEscapeHtml(movement.type),
            stockEscapeHtml(movement.location),
            stockEscapeHtml(movement.quantity),
            stockEscapeHtml(movement.previous_balance),
            stockEscapeHtml(movement.new_balance),
            stockEscapeHtml(movement.created_by || '—'),
        ]);

        return stockRenderTable(['Date', 'Type', 'Location', 'Qty', 'Previous', 'New', 'By'], rows, 'No movements recorded for this product.', 'movements');
    }

    function stockRenderAdjustments(details) {
        const rows = (details.adjustments || []).map((adjustment) => [
            stockEscapeHtml(adjustment.number),
            stockEscapeHtml(adjustment.date),
            stockEscapeHtml(adjustment.location),
            stockEscapeHtml(adjustment.reason || '—'),
            stockEscapeHtml(adjustment.type),
            stockEscapeHtml(adjustment.change),
            stockEscapeHtml(adjustment.status),
        ]);

        return stockRenderTable(['Number', 'Date', 'Location', 'Reason', 'Type', 'Change', 'Status'], rows, 'No adjustments recorded for this product.', 'adjustments');
    }

    function stockRenderTransfers(details) {
        const rows = (details.transfers || []).map((transfer) => [
            stockEscapeHtml(transfer.number),
            stockEscapeHtml(transfer.date),
            stockEscapeHtml(transfer.from),
            stockEscapeHtml(transfer.to),
            stockEscapeHtml(transfer.quantity),
            stockEscapeHtml(transfer.status),
        ]);

        return stockRenderTable(['Number', 'Date', 'From', 'To', 'Qty', 'Status'], rows, 'No transfers recorded for this product.', 'transfers');
    }

    function stockRenderDamage(details) {
        const rows = (details.damages || []).map((damage) => [
            stockEscapeHtml(damage.number),
            stockEscapeHtml(damage.date),
            stockEscapeHtml(damage.location),
            stockEscapeHtml(damage.quantity),
            stockEscapeHtml(damage.type || '—'),
            stockEscapeHtml(damage.severity || '—'),
            stockEscapeHtml(damage.status),
        ]);

        return stockRenderTable(['Number', 'Date', 'Location', 'Qty', 'Type', 'Severity', 'Status'], rows, 'No damage reports for this product.', 'damage');
    }

    function stockRenderCounts(details) {
        const countRows = (details.counts || []).map((count) => [
            stockEscapeHtml(count.number),
            stockEscapeHtml(count.date),
            stockEscapeHtml(count.location),
            stockEscapeHtml(count.expected),
            stockEscapeHtml(count.counted),
            stockEscapeHtml(count.variance),
            stockEscapeHtml(count.status),
        ]);

        const scheduleRows = (details.count_schedules || []).map((schedule) => [
            stockEscapeHtml(schedule.name),
            stockEscapeHtml(schedule.location),
            stockEscapeHtml(schedule.count_type || '—'),
            stockEscapeHtml(schedule.frequency || '—'),
            stockEscapeHtml(schedule.next_count_date || '—'),
            schedule.is_active ? 'Active' : 'Inactive',
        ]);

        return `
            <h4 style="margin:0 0 .75rem 0;font-size:.9rem;color:var(--headings);">Stock Counts</h4>
            ${stockRenderTable(['Number', 'Date', 'Location', 'Expected', 'Counted', 'Variance', 'Status'], countRows, 'No stock counts for this product.', 'counts')}
            <h4 style="margin:1.5rem 0 .75rem 0;font-size:.9rem;color:var(--headings);">Count Schedules</h4>
            ${stockRenderTable(['Name', 'Location', 'Type', 'Frequency', 'Next Date', 'Status'], scheduleRows, 'No count schedules for this location.', 'counts')}
        `;
    }

    function stockRenderReturns(details) {
        const rows = (details.returns || []).map((item) => [
            stockEscapeHtml(item.number),
            stockEscapeHtml(item.date),
            stockEscapeHtml(item.location),
            stockEscapeHtml(item.quantity),
            stockEscapeHtml(item.reason || '—'),
            stockEscapeHtml(item.condition || '—'),
            stockEscapeHtml(item.status),
        ]);

        return stockRenderTable(['Number', 'Date', 'Location', 'Qty', 'Reason', 'Condition', 'Status'], rows, 'No returns recorded for this product.', 'returns');
    }

    function stockRenderLocation(details) {
        const locationRows = (details.locations || []).map((location) => [
            stockEscapeHtml(location.name),
            stockEscapeHtml(location.type || '—'),
            stockEscapeHtml(location.address || '—'),
            location.is_default ? 'Yes' : 'No',
            location.is_active ? 'Active' : 'Inactive',
        ]);

        const binRows = (details.bins || []).map((bin) => [
            stockEscapeHtml(bin.code || '—'),
            stockEscapeHtml(bin.type || '—'),
            stockEscapeHtml(bin.location || '—'),
            bin.is_active ? 'Active' : 'Inactive',
        ]);

        return `
            <h4 style="margin:0 0 .75rem 0;font-size:.9rem;color:var(--headings);">Stock Locations</h4>
            ${stockRenderTable(['Name', 'Type', 'Address', 'Default', 'Status'], locationRows, 'No locations linked to this product.', 'location')}
            <h4 style="margin:1.5rem 0 .75rem 0;font-size:.9rem;color:var(--headings);">Bins</h4>
            ${stockRenderTable(['Code', 'Type', 'Location', 'Status'], binRows, 'No bins configured for this product.', 'bins')}
        `;
    }

    function renderStockViewTab(tabId, productId) {
        const details = stockDetailsByProduct[productId];
        const content = document.getElementById('stockViewTabContent');
        if (!details || !content) {
            return;
        }

        const renderers = {
            overview: stockRenderOverview,
            movements: stockRenderMovements,
            adjustments: stockRenderAdjustments,
            transfers: stockRenderTransfers,
            damage: stockRenderDamage,
            counts: stockRenderCounts,
            returns: stockRenderReturns,
            location: stockRenderLocation,
        };

        content.innerHTML = (renderers[tabId] || stockRenderOverview)(details);
    }

    function resetStockViewTabs() {
        const container = document.getElementById('stockViewTabs');
        if (!container) {
            return;
        }

        container.querySelectorAll('.flowexa-tab-trigger').forEach((trigger, index) => {
            const isActive = index === 0;
            trigger.classList.toggle('flowexa-tab-active', isActive);
            trigger.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });
    }

    function viewStockDetails(productId) {
        const details = stockDetailsByProduct[productId];
        if (!details) {
            return;
        }

        currentStockViewProductId = productId;
        resetStockViewTabs();
        renderStockViewTab('overview', productId);

        const header = document.getElementById('stockViewModalHeader');
        if (header) {
            header.innerHTML = `
                <p style="margin:0;color:var(--text-secondary);font-size:.85rem;">
                    Viewing stock records for <strong style="color:var(--headings);">${stockEscapeHtml(details.product.name)}</strong>
                </p>
            `;
        }

        openflowexaModal('stockViewModal');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.getElementById('stockViewTabs');
        if (!tabs) {
            return;
        }

        tabs.addEventListener('tab-changed', function (event) {
            if (currentStockViewProductId) {
                renderStockViewTab(event.detail.tabId, currentStockViewProductId);
            }
        });
    });
</script>
