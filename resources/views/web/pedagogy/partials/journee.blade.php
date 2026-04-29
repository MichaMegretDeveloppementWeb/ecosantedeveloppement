@php
    $moments = [
        ['time' => '7h30 à 9h00',   'title' => 'Accueil échelonné',     'desc' => 'Un temps doux pour se séparer, raconter sa nuit, retrouver ses repères et son groupe.'],
        ['time' => '9h00 à 11h00',  'title' => "Activités d'éveil",      'desc' => 'Motricité libre, ateliers sensoriels, peinture, musique. Au choix de chaque enfant.'],
        ['time' => '11h00 à 12h30', 'title' => 'Repas bio',             'desc' => 'Préparé sur place, en cuisine ouverte, avec des produits frais 100% bio.'],
        ['time' => '12h30 à 15h00', 'title' => 'Sieste & repos',        'desc' => 'Chacun dort à son rythme, dans un dortoir paisible, avec son doudou.'],
        ['time' => '15h00 à 17h00', 'title' => 'Goûter & jeux libres',  'desc' => "Lecture, jeux d'imitation, sortie au parc, atelier créatif selon la météo."],
        ['time' => '17h00 à 18h30', 'title' => 'Retrouvailles',         'desc' => "Transmissions personnalisées\u{00A0}: la journée racontée, les progrès, les anecdotes."],
    ];
@endphp

<section class="section journee-section-projet" id="journee">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Une journée chez nous</span>
            <h2>Du matin au soir,<br><em class="italic-accent">à leur rythme</em>.</h2>
        </div>

        <div class="moments-grid">
            @foreach ($moments as $i => $m)
                <div class="moment moment-{{ $i + 1 }}">
                    <span class="moment-time">{{ $m['time'] }}</span>
                    <h4>{{ $m['title'] }}</h4>
                    <p>{{ $m['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
