@php
    $org = config('eco-sante.organization');
    $creches = config('eco-sante.creches');
    $pdfPath = config('eco-sante.contact.preinscription_pdf_path');
@endphp

<aside class="contact-side">
    <div class="contact-card">
        <h3>Coordonnées</h3>
        <ul class="coord-list">
            <li>
                <span class="coord-icon coord-icon-rose">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                </span>
                <div>
                    <span class="muted">Téléphone</span>
                    <a href="tel:{{ $org['phone_raw'] }}"><strong>{{ $org['phone'] }}</strong></a>
                </div>
            </li>
            <li>
                <span class="coord-icon coord-icon-bleu">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </span>
                <div>
                    <span class="muted">Courriel</span>
                    <a href="mailto:{{ $org['email'] }}"><strong>{{ $org['email'] }}</strong></a>
                </div>
            </li>
            <li>
                <span class="coord-icon coord-icon-jaune">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg>
                </span>
                <div>
                    <span class="muted">Horaires d'accueil</span>
                    <strong>{{ $org['opening_hours']['days'] }}<br>{{ $org['opening_hours']['hours'] }}</strong>
                </div>
            </li>
        </ul>
    </div>

    <div class="contact-card download-card">
        <x-illu.livre class="download-icon" />
        <h3>Fiche de préinscription</h3>
        <p>Téléchargez la fiche, complétez-la, et joignez-la à votre message ci-contre.</p>
        <a href="{{ asset($pdfPath) }}" download class="btn btn-secondary mt-4">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
            Télécharger (PDF)
        </a>
    </div>

    <div class="contact-card mini-creches">
        <h4>Nos trois crèches</h4>
        @foreach ($creches as $creche)
            <p class="mini-c">
                <span class="dot dot-{{ $creche['palette'] }}"></span>
                <strong>{{ $creche['name'] }}</strong><br>
                <span class="muted">{{ $creche['city'] }} · {{ $creche['department_code'] }}</span>
            </p>
        @endforeach
    </div>
</aside>
