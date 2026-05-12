@php
    // Témoignages basés sur de vrais retours parents, raccourcis et anonymisés
    // (prénoms d'enfants retirés). À étoffer si d'autres parents donnent leur accord.
    $temoignages = [
        [
            'palette' => 'rose',
            'initials' => 'M',
            'quote' => "Il ne s'est jamais senti aussi bien dans une crèche. Une équipe formidable, bienveillante, à qui nous avons pu le confier en totale confiance \u{2014} c'est inestimable pour un parent.",
            'author' => 'Une maman',
            'context' => "Parents d'un petit garçon",
        ],
        [
            'palette' => 'jaune',
            'initials' => 'F',
            'quote' => "Notre fille a passé deux belles années chez vous. Elle était toujours contente d'y aller, appréciait toute l'équipe, et s'y est faite de vraies copines. Merci pour votre flexibilité.",
            'author' => 'Une famille',
            'context' => "Deux années d'accueil",
        ],
        [
            'palette' => 'bleu',
            'initials' => 'P',
            'quote' => "Nous sommes vraiment satisfaits du travail accompli au quotidien. Notre enfant s'est totalement épanoui \u{2014} toute l'équipe est adorable, dans un cadre sain et équilibré.",
            'author' => 'Des parents',
            'context' => "Parents d'un enfant accueilli",
        ],
    ];
@endphp

<section class="section temoignages-section">
    <div class="container">
        <div class="section-head text-center">
            <span class="eyebrow">Paroles de parents</span>
            <h2>Ce qu'ils en disent.</h2>
        </div>

        <div class="temoignages-grid">
            @foreach ($temoignages as $t)
                <figure class="temoignage">
                    <div class="quote-mark">&ldquo;</div>
                    <blockquote>{{ $t['quote'] }}</blockquote>
                    <figcaption>
                        <span class="avatar avatar-{{ $t['palette'] }}">{{ $t['initials'] }}</span>
                        <div>
                            <strong>{{ $t['author'] }}</strong>
                            <span class="muted">{{ $t['context'] }}</span>
                        </div>
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</section>
