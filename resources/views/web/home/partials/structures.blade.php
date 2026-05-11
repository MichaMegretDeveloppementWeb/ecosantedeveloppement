@php
    $creches = config('eco-sante.creches');
@endphp

<section class="section structures-section">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Nos structures</span>
            <h2>Trois <em class="italic-accent">lieux d'accueil</em>.</h2>
            <p class="lede">Découvrez nos trois micro-crèches, deux dans le Val d'Oise et une en Savoie.</p>
        </div>

        <div class="structure-cards">
            @foreach ($creches as $creche)
                @php
                    $palette = $creche['palette']; // rose | jaune | bleu
                    $pillClass = $palette === 'rose' ? 'pill' : 'pill pill-' . $palette;
                @endphp
                <a href="{{ route('creches.index') }}#{{ $creche['slug'] }}" class="structure-card structure-card-{{ $palette }}">
                    {{-- Photo de la crèche en cover + petite illustration en surimpression
                         pour conserver l'âme illustrée du design. --}}
                    <div class="structure-photo">
                        <img src="{{ asset($creche['photo']) }}" alt="Vue de la micro-crèche {{ $creche['name'] }}" loading="lazy">
                        <span class="structure-photo-badge">
                            <x-dynamic-component :component="'illu.' . $creche['main_illu']" class="illu" />
                        </span>
                    </div>

                    {{--
                        === VERSION PRÉCÉDENTE — illustration centrée à la place de la photo ===
                        Pour revenir, décommenter le bloc ci-dessous et supprimer
                        le <div class="structure-photo"> juste au-dessus.

                        <div class="structure-illu">
                            <x-dynamic-component :component="'illu.' . $creche['main_illu']" class="illu" />
                        </div>
                    --}}

                    <div class="structure-body">
                        <span class="{{ $pillClass }}">{{ $creche['department'] }} · {{ $creche['department_code'] }}</span>
                        <h3>{{ $creche['name'] }}</h3>
                        <p class="muted">{{ $creche['address'] }}<br>{{ $creche['postal_code'] }} {{ $creche['city'] }}</p>
                        <div class="structure-meta">
                            <span><strong>{{ $creche['capacity'] }}</strong> enfants</span>
                            <span class="dot">·</span>
                            <span>Ouverte 5j/7</span>
                        </div>
                        <span class="structure-link">En savoir plus →</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
