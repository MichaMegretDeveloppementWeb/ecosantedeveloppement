@php
    $isActive = fn (string $name) => request()->routeIs($name) ? 'active' : '';
@endphp

<header class="site-header">
    <div class="container">
        <nav class="nav">
            <a href="{{ route('home') }}" class="nav-logo">
                <x-illu.logo class="logo-icon" />
                <span class="nav-logo-name">{{ config('eco-sante.organization.name', 'Éco Santé Développement') }}</span>
            </a>

            <div class="nav-links" id="nav-links">
                <a href="{{ route('home') }}" class="{{ $isActive('home') }}">Accueil</a>
                <a href="{{ route('creches.index') }}" class="{{ $isActive('creches.index') }}">Nos crèches</a>
                <a href="{{ route('pedagogy.index') }}" class="{{ $isActive('pedagogy.index') }}">Projet pédagogique</a>
                <a href="{{ route('contact.index') }}" class="{{ $isActive('contact.index') }}">Contact</a>
                <a href="{{ route('contact.index') }}" class="btn btn-primary">Inscription</a>
            </div>

            <button class="nav-toggle" id="nav-toggle" aria-label="Menu" aria-expanded="false" aria-controls="nav-links">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <line x1="4" y1="7" x2="20" y2="7"/>
                    <line x1="4" y1="12" x2="20" y2="12"/>
                    <line x1="4" y1="17" x2="20" y2="17"/>
                </svg>
            </button>
        </nav>
    </div>
</header>
