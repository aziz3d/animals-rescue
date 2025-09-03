<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? setting('site_name', config('app.name')) }}</title>
<meta name="description" content="{{ setting('meta_description', 'Admin panel for managing site content') }}">
<meta name="keywords" content="{{ setting('site_keywords', 'admin, management, dashboard') }}">

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

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
