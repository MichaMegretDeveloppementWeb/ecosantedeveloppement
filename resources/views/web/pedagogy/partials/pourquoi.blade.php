@php
    $raisons = [
        ['num' => '01', 'title' => 'Accueil en petit groupe',          'desc' => '10 à 12 enfants par structure, pour un vrai accompagnement individualisé.'],
        ['num' => '02', 'title' => 'Projet éducatif bienveillant',     'desc' => 'Inspiré de Montessori et de la pédagogie positive, sans dogmatisme.'],
        ['num' => '03', 'title' => "Flexibilité d'accueil",            'desc' => 'Temps plein, partiel, accueil occasionnel, selon vos besoins.'],
        ['num' => '04', 'title' => 'Suivi individualisé',              'desc' => 'Carnet de transmissions, rendez-vous trimestriels avec les familles.'],
        ['num' => '05', 'title' => 'Environnement stimulant et sécurisé', 'desc' => "Locaux pensés pour l'éveil, équipe stable, climat de confiance."],
    ];
@endphp

<section class="section pourquoi-section">
    <div class="container">
        <div class="pourquoi-grid">
            <div>
                <span class="eyebrow">Pourquoi nous choisir</span>
                <h2 class="mb-5">Cinq bonnes raisons,<br><em class="italic-accent">en toute simplicité</em>.</h2>
            </div>
            <ul class="pourquoi-list">
                @foreach ($raisons as $r)
                    <li>
                        <span class="pourquoi-num">{{ $r['num'] }}</span>
                        <div>
                            <strong>{{ $r['title'] }}</strong>
                            <p>{{ $r['desc'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
