@php
    // Témoignages PLACEHOLDER : à remplacer par de vrais retours parents (avec accord).
    $temoignages = [
        [
            'palette' => 'rose',
            'initials' => 'CM',
            'quote' => "Une équipe formidable, à l'écoute, qui prend vraiment le temps. Notre fille part le matin avec le sourire, c'est le plus beau des cadeaux pour des parents.",
            'author' => 'Camille & Marc',
            'context' => 'Parents de Léa, 18 mois',
        ],
        [
            'palette' => 'jaune',
            'initials' => 'SD',
            'quote' => "Le petit effectif fait toute la différence. On nous raconte sa journée en détail, on sent que chaque enfant est connu, écouté, respecté.",
            'author' => 'Sophie D.',
            'context' => 'Maman de Théo, 2 ans',
        ],
        [
            'palette' => 'bleu',
            'initials' => 'JR',
            'quote' => "Les repas bio préparés sur place, l'attention portée au sommeil, les activités variées, tout est pensé. On part au travail l'esprit léger.",
            'author' => 'Julien R.',
            'context' => 'Papa de Anna, 14 mois',
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
