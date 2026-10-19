<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta --}}
    @hasSection('title')
        <title>@yield('title')</title>
    @else
        <title>{{ $site->name ?? config('app.name') }}</title>
    @endif

    @hasSection('meta_description')
        <meta name="description" content="@yield('meta_description')">
    @endif

    @hasSection('meta_keywords')
        <meta name="keywords" content="@yield('meta_keywords')">
    @endif

    @hasSection('og_image')
        <meta property="og:image" content="@yield('og_image')">
    @endif

    {{-- Partial SEO Meta (jika ada) --}}
    @includeIf('layouts.partials.seo-meta')

    {{-- Favicon --}}
    @if($theme?->favicon_path)
        <link rel="icon" href="{{ Storage::url($theme->favicon_path) }}">
    @endif

    {{-- Theme Variables dari Database --}}
    <style>
        :root {
            --theme-primary: {{ $theme->primary_color ?? '#F59E0B' }};
            --theme-secondary: {{ $theme->secondary_color ?? '#EA580C' }};
            --theme-accent: {{ $theme->accent_color ?? '#FBBF24' }};
            --theme-background: {{ $theme->background_color ?? '#FFFFFF' }};
            --theme-text: {{ $theme->text_color ?? '#111827' }};
            --font-sans: '{{ $theme->font_family ?? 'Inter' }}', ui-sans-serif, system-ui, sans-serif;
            --font-heading: '{{ $theme->heading_font ?? 'Poppins' }}', ui-sans-serif, system-ui, sans-serif;
        }
    </style>

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Stack Styles (untuk @push('styles') di view) --}}
    @stack('styles')

    {{-- Schema Markup --}}
    @stack('schema')

    {{-- Extra Head --}}
    @stack('head')
</head>
<body class="font-sans antialiased" style="color: var(--theme-text); background-color: var(--theme-background);">
    {{-- Navbar --}}
    @include('layouts.partials.navbar')

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.partials.footer')

    {{-- WhatsApp Floating Button --}}
    @include('layouts.partials.whatsapp')

    {{-- Stack Scripts (untuk @push('scripts') di view) --}}
    @stack('scripts')
</body>
</html>