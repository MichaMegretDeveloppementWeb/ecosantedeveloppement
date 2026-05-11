@php
    $org = config('eco-sante.organization');
@endphp

<section class="cta-section">
    <div class="container">
        <div class="cta-card">
            <x-illu.ballon class="cta-illu cta-illu-1" />
            <x-illu.enfant class="cta-illu cta-illu-2" />
            <x-illu.etoile class="cta-illu cta-illu-3" />
            <div class="cta-content">
                <span class="eyebrow">Nous contacter</span>
                <h2>Pour plus d'informations.</h2>
                <p class="lede">
                    Une question, une préinscription, un échange&nbsp;? Appelez-nous, nous
                    sommes joignables du lundi au vendredi.
                </p>
                <div class="flex gap-3 mt-5" style="flex-wrap:wrap;">
                    <a href="tel:{{ $org['phone_raw'] }}" class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                        {{ $org['phone'] }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
