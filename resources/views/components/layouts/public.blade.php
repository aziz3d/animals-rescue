<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? setting('meta_title', 'Lovely Paws Rescue') }}</title>
    <meta name="description" content="{{ setting('meta_description', 'Lovely Paws Rescue - Dedicated to rescuing, rehabilitating, and rehoming animals in need. Every paw deserves love, care, and a forever home.') }}">
    <meta name="keywords" content="{{ setting('site_keywords', 'animal rescue, pet adoption, dogs, cats, volunteers') }}">

    @php
        $faviconPath = setting('site_favicon');
    @endphp
    @if($faviconPath)
        @php
            // Use our improved setting_asset helper which handles multiple fallbacks
            $faviconUrl = setting_asset('site_favicon');
            $faviconExtension = pathinfo($faviconPath, PATHINFO_EXTENSION);
            $mimeType = match(strtolower($faviconExtension)) {
                'ico' => 'image/x-icon',
                'png' => 'image/png',
                'jpg', 'jpeg' => 'image/jpeg',
                'svg' => 'image/svg+xml',
                'gif' => 'image/gif',
                default => 'image/x-icon'
            };
        @endphp
        <link rel="icon" href="{{ $faviconUrl }}" type="{{ $mimeType }}">
        <link rel="shortcut icon" href="{{ $faviconUrl }}" type="{{ $mimeType }}">
        @if(in_array(strtolower($faviconExtension), ['png', 'jpg', 'jpeg']))
            <link rel="apple-touch-icon" href="{{ $faviconUrl }}">
        @endif
    @else
        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/image-fallback.js'])
</head>
<body class="bg-amber-50 text-amber-900 antialiased">
    <!-- Skip Navigation Link -->
    <a href="#main-content" class="skip-nav focus-visible">Skip to main content</a>

    <!-- Navigation -->
    <x-public.navigation />

    <!-- Main Content -->
    <main id="main-content" tabindex="-1">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <x-public.footer />

    <!-- Screen Reader Announcements -->
    <div aria-live="polite" aria-atomic="true" class="sr-only" id="sr-announcements"></div>
</body>
</html>