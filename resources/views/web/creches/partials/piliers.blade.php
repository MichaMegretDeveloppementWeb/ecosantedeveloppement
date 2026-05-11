@php
    $piliers = [
        [
            'icon' => 'feuille',
            'palette' => 'sauge',
            'title' => 'Une même philosophie, trois lieux d\'accueil',
            'text' => "Nos micro-crèches partagent une vision commune\u{00A0}: offrir un cadre bienveillant où l'enfant peut grandir en confiance, s'éveiller et développer son autonomie.",
        ],
        [
            'icon' => 'enfant',
            'palette' => 'rose',
            'title' => 'Un accueil personnalisé',
            'text' => "Avec un effectif limité, chaque enfant bénéficie d'une attention particulière, favorisant un lien fort avec les professionnels de la petite enfance.",
        ],
        [
            'icon' => 'etoile',
            'palette' => 'jaune',
            'title' => "L'éveil au cœur du quotidien",
            'text' => "Activités sensorielles, motricité libre, lecture, musique, jeux d'imitation… tout est pensé pour stimuler la curiosité et le développement global de l'enfant.",
        ],
        [
            'icon' => 'coeur',
            'palette' => 'bleu',
            'title' => 'Une relation de confiance avec les familles',
            'text' => "Nous plaçons les parents au cœur du projet éducatif. La communication quotidienne permet un suivi transparent et rassurant.",
        ],
    ];
@endphp

<section class="section piliers-section">
    <div class="container">
        <div class="section-head text-center">
            <span class="eyebrow">Nos engagements</span>
            <h2>Quatre piliers<br><em class="italic-accent">au service de l'enfant</em>.</h2>
        </div>

        <div class="piliers-grid">
            @foreach ($piliers as $p)
                <article class="pilier">
                    <div class="pilier-icon pilier-icon-{{ $p['palette'] }}">
                        <x-dynamic-component :component="'illu.' . $p['icon']" class="illu" />
                    </div>
                    <h3>{{ $p['title'] }}</h3>
                    <p>{{ $p['text'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
