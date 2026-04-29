@php
    $stats = config('eco-sante.organization.stats');
@endphp

<section class="hero">
    <div class="hero-blob hero-blob-1"></div>
    <div class="hero-blob hero-blob-2"></div>
    <div class="hero-blob hero-blob-3"></div>

    <div class="container hero-grid">
        <div class="hero-text">
            <span class="eyebrow">Bienvenue chez {{ config('eco-sante.organization.name') }}</span>
            <h1>
                Un lieu pensé<br>
                <em class="italic-accent">pour vos tout-petits</em>,<br>
                à taille humaine.
            </h1>
            <p class="lede mb-6">
                Nos trois micro-crèches privées accueillent vos enfants dans un environnement
                chaleureux, sécurisé et adapté à leurs premiers apprentissages, pour grandir
                en confiance, à leur rythme.
            </p>
            <div class="flex gap-3" style="flex-wrap:wrap;">
                <a href="{{ route('creches.index') }}" class="btn btn-primary">
                    Découvrir nos crèches
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
                <a href="{{ route('contact.index') }}" class="btn btn-secondary">Demander une visite</a>
            </div>

            <div class="hero-stats">
                <div>
                    <strong>{{ $stats['structures_count'] }}</strong>
                    <span>structures</span>
                </div>
                <div class="hero-stat-divider"></div>
                <div>
                    <strong>{{ $stats['children_capacity'] }}</strong>
                    <span>enfants accueillis</span>
                </div>
                <div class="hero-stat-divider"></div>
                <div>
                    <strong>{{ $stats['organic_meals_percent'] }}%</strong>
                    <span>repas bio sur place</span>
                </div>
            </div>
        </div>

        <div class="hero-visual">
            <div class="hero-card">
                <x-illu.maison    class="hero-illu hero-illu-house" />
                <x-illu.soleil    class="hero-illu hero-illu-sun" />
                <x-illu.nuage     class="hero-illu hero-illu-cloud" />
                <x-illu.fleur     class="hero-illu hero-illu-flower" />
                <x-illu.feuille   class="hero-illu hero-illu-leaf" />
                <x-illu.papillon  class="hero-illu hero-illu-butterfly" />
            </div>
            <div class="hero-badge">
                <x-illu.coeur class="badge-icon" />
                <div>
                    <strong>Agréées PMI</strong>
                    <span>Val d'Oise &amp; Savoie</span>
                </div>
            </div>
        </div>
    </div>
</section>
