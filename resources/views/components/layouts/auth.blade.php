<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts: Instrument Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') ?? '/css/app.css' }}">
    @endif
    
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
            background-color: var(--background);
            color: var(--text-primary);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .background-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .synkra-layout-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .synkra-main-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            margin-left: 260px; /* Width of expanded sidebar */
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
        }

        /* When sidebar is collapsed */
        .synkra-main-wrapper.expanded {
            margin-left: 80px; /* Width of collapsed sidebar */
        }
        
        /* For guest views or when unauthenticated */
        .synkra-main-wrapper.full-width {
            margin-left: 0;
        }

        .synkra-main-content {
            padding: 2rem;
            flex-grow: 1;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <x-ui.loader />
    <div class="background-pattern">
        <x-ui.grid />
    </div>

    <div class="synkra-layout-wrapper">
        {{ $slot ?? '' }}
        @yield('content')
    </div>

</body>
</html>
