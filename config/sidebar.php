<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sidebar item indicators
    |--------------------------------------------------------------------------
    |
    | Maps permission actions to indicator resolvers. Badges are only shown when
    | the user has the matching permission assigned for that menu item.
    |
    | rollup_parent: when true, the indicator also appears on the module parent.
    |
    */
    'indicators' => [
        'view_reorder_alerts' => [
            'resolver' => 'reorder_alerts',
            'rollup_parent' => true,
            'label' => 'Reorder Alerts',
        ],
    ],
];
