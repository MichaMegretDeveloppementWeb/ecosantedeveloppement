@php
    $org = config('eco-sante.organization');
    $creches = config('eco-sante.creches');
    $year = now()->year;
@endphp

<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">

            <div>
                <div class="footer-brand">
                    <span class="footer-brand-icon">
                        <x-illu.logo />
                    </span>
                    <strong class="footer-brand-name">{{ $org['name'] }}</strong>
                </div>
                <p class="footer-tagline">
                    {{ $org['tagline'] }}
                </p>
            </div>

            <div>
                <h4>Nos crèches</h4>
                @foreach ($creches as $creche)
                    <a href="{{ route('creches.index') }}#{{ $creche['slug'] }}">
                        {{ $creche['name'] }} · {{ $creche['city'] }}
                    </a>
                @endforeach
            </div>

            <div>
                <h4>Le projet</h4>
                <a href="{{ route('pedagogy.index') }}">Projet pédagogique</a>
                <a href="{{ route('pedagogy.index') }}#valeurs">Nos valeurs</a>
                <a href="{{ route('pedagogy.index') }}#journee">Une journée type</a>
                <a href="{{ route('pedagogy.index') }}#equipe">L'équipe</a>
            </div>

            <div>
                <h4>Contact</h4>
                <a href="tel:{{ $org['phone_raw'] }}">{{ $org['phone'] }}</a>
                <a href="mailto:{{ $org['email'] }}">{{ $org['email'] }}</a>
                <a href="{{ route('contact.index') }}">Demande d'inscription</a>
                <a href="{{ route('legal.index') }}">Mentions légales</a>
            </div>

        </div>

        <div class="footer-bottom">
            <span>© {{ $year }} {{ $org['name'] }} · Tous droits réservés</span>
            <span>Structures agréées PMI · Val d'Oise &amp; Savoie</span>
        </div>
    </div>
</footer>
