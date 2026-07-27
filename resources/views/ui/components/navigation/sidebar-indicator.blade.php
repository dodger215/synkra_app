@php
    $indicator = $indicator ?? null;

    $tooltipParts = [];
    if (!empty($indicator['critical'])) {
        $tooltipParts[] = $indicator['critical'] . ' critical';
    }
    if (!empty($indicator['low'])) {
        $tooltipParts[] = $indicator['low'] . ' low';
    }
    if (!empty($indicator['active_alerts'])) {
        $tooltipParts[] = $indicator['active_alerts'] . ' active';
    }

    $tooltip = $tooltipParts
        ? implode(', ', $tooltipParts)
        : (($indicator['count'] ?? 0) . ' alert(s)');
@endphp

@if(!empty($indicator['count']))
    <div class="flowexa-sidebar-indicator-group" title="{{ $tooltip }}">
        <x-ui.badge
            :text="(string) $indicator['count']"
            :variant="$indicator['variant'] ?? 'info'"
            pill="true"
            class="flowexa-sidebar-item-badge"
        />

        @if(!empty($indicator['critical']) || !empty($indicator['low']) || !empty($indicator['active_alerts']))
            <span class="flowexa-sidebar-indicator-numbers">
                @if(!empty($indicator['critical']))
                    <span class="flowexa-sidebar-indicator-num flowexa-sidebar-indicator-num-danger" title="Critical">{{ $indicator['critical'] }}</span>
                @endif
                @if(!empty($indicator['low']))
                    <span class="flowexa-sidebar-indicator-num flowexa-sidebar-indicator-num-warning" title="Low stock">{{ $indicator['low'] }}</span>
                @endif
                @if(!empty($indicator['active_alerts']))
                    <span class="flowexa-sidebar-indicator-num flowexa-sidebar-indicator-num-info" title="Active alerts">{{ $indicator['active_alerts'] }}</span>
                @endif
            </span>
        @endif
    </div>
@endif
