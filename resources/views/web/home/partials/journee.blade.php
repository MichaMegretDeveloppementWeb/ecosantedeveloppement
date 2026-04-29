@php
    $steps = [
        ['time' => '7h30',  'title' => 'Accueil échelonné',     'desc' => 'Un temps doux pour se séparer en confiance, raconter sa nuit, retrouver ses repères.'],
        ['time' => '9h30',  'title' => "Activités d'éveil",     'desc' => 'Motricité libre, ateliers sensoriels, peinture, musique, au choix de chaque enfant.'],
        ['time' => '11h30', 'title' => 'Repas 100% bio',        'desc' => 'Préparé sur place avec des produits frais, adapté à l\'âge et aux régimes de chacun.'],
        ['time' => '12h30', 'title' => 'Sieste & repos',        'desc' => 'Chacun dort à son rythme, dans un dortoir paisible avec son doudou.'],
        ['time' => '15h30', 'title' => 'Goûter & jeux libres',  'desc' => 'Lecture, jeux d\'imitation, sortie au parc, selon la météo et l\'envie du groupe.'],
        ['time' => '17h30', 'title' => 'Retrouvailles',         'desc' => "Transmissions aux parents\u{00A0}: la journée racontée, les progrès, les anecdotes."],
    ];
@endphp

<section class="section journee-section">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Une journée chez nous</span>
            <h2>Du matin au soir,<br><em class="italic-accent">à leur rythme</em>.</h2>
        </div>

        <div class="journee-timeline">
            <div class="journee-line"></div>
            @foreach ($steps as $step)
                <div class="journee-step">
                    <div class="journee-time">{{ $step['time'] }}</div>
                    <div class="journee-dot"></div>
                    <div class="journee-card">
                        <strong>{{ $step['title'] }}</strong>
                        <p>{{ $step['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
