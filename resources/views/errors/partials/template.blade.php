@php
    /** @var string $code */
    /** @var string $title */
    /** @var string $lede */
    /** @var string $illu  Nom du composant illustration (ex: 'fleur'). */
    /** @var string|null $primaryLabel */
    /** @var string|null $primaryUrl */
@endphp

<section class="error-page">
    <div class="error-blob error-blob-1"></div>
    <div class="error-blob error-blob-2"></div>
    <div class="error-blob error-blob-3"></div>

    <div class="container">
        <div class="error-illu-wrapper">
            <x-dynamic-component :component="'illu.' . $illu" />
        </div>

        <p class="error-code">{{ $code }}</p>
        <h1>{{ $title }}</h1>
        <p class="lede">{{ $lede }}</p>

        <div class="error-actions">
            <a href="{{ $primaryUrl ?? route('home') }}" class="btn btn-primary">
                {{ $primaryLabel ?? "Retour à l'accueil" }}
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>
            <a href="{{ route('contact.index') }}" class="btn btn-secondary">Nous contacter</a>
        </div>
    </div>
</section>
