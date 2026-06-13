@include('ui.components.auth.card', array_merge([
    'slot' => $slot ?? null, 
    'header' => $header ?? null
], $attributes->getAttributes()))
