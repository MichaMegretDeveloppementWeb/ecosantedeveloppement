@php
    $orientations = [
        ['num' => '01', 'illu' => 'maison',  'title' => 'Un environnement sain, sûr et stimulant', 'text' => 'Des locaux conformes aux normes PMI, des matériaux choisis pour leur innocuité, des espaces pensés pour la motricité libre. Tout est vérifié, entretenu, repensé en permanence avec les enfants au centre.'],
        ['num' => '02', 'illu' => 'feuille', 'title' => "L'apprentissage des comportements favorables à la santé", 'text' => "Repas bio préparés sur place, respect des rythmes de sommeil, hygiène douce, premiers gestes du quotidien\u{00A0}: l'enfant apprend par l'exemple et par l'expérience, dans un climat bienveillant."],
        ['num' => '03', 'illu' => 'etoile',  'title' => 'Le développement des compétences psychosociales', 'text' => 'Activités sensorielles, motricité libre, lecture, musique, jeux d\'imitation… Tout est pensé pour stimuler la curiosité, l\'autonomie, la confiance en soi et la relation aux autres.'],
        ['num' => '04', 'illu' => 'coeur',   'title' => 'Des liens avec les services de santé et la communauté', 'text' => 'Suivi PMI, partenariats avec professionnels de santé, ouverture sur le quartier, lien intergénérationnel. La crèche n\'est jamais isolée de son écosystème, elle s\'y inscrit pleinement.'],
    ];
@endphp

<section class="section orientations-section" id="valeurs">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Quatre orientations</span>
            <h2>Notre cadre de vie<br><em class="italic-accent">repose sur quatre piliers</em>.</h2>
            <p class="lede">
                Chaque action quotidienne au sein de nos crèches s'articule autour de ces
                quatre orientations stratégiques.
            </p>
        </div>

        <div class="orientations-list">
            @foreach ($orientations as $o)
                <article class="orientation">
                    <span class="orientation-number">{{ $o['num'] }}</span>
                    <div class="orientation-content">
                        <h3>{{ $o['title'] }}</h3>
                        <p>{{ $o['text'] }}</p>
                    </div>
                    <span class="orientation-illu">
                        <x-dynamic-component :component="'illu.' . $o['illu']" class="illu" />
                    </span>
                </article>
            @endforeach
        </div>
    </div>
</section>
