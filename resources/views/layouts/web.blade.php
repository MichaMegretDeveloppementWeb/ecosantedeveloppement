<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('eco-sante.organization.name')) · @yield('subtitle', 'Trois micro-crèches privées')</title>

    @if(trim($__env->yieldContent('description')))
        <meta name="description" content="@yield('description')">
    @endif

    <link rel="icon" href="{{ asset('favicon.ico') }}">

    {{-- Globaux --}}
    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/css/components/layout/header.css',
        'resources/css/components/layout/footer.css',
    ])

    {{-- Assets spécifiques à la page --}}
    @stack('assets')

    {{-- SEO : JSON-LD. Organization injectée globalement,
         chaque page peut pousser ses propres schemas additionnels. --}}
    <x-seo.organization-schema />
    @stack('schema')

    @livewireStyles
</head>
<body data-page="@yield('page', '')">

    <x-layout.header />

    <main>
        @yield('content')
    </main>

    <x-layout.footer />

    @livewireScripts
</body>
</html>
