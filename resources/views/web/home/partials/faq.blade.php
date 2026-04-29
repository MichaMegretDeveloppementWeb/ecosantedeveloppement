@php
    $faqs = [
        [
            'q' => "À partir de quel âge accueillez-vous les enfants\u{00A0}?",
            'a' => "Nous accueillons les enfants dès 10 semaines (sortie du congé maternité) jusqu'à 3 ans, ou jusqu'à l'entrée à l'école maternelle.",
            'open' => true,
        ],
        [
            'q' => "Quels sont les horaires d'ouverture\u{00A0}?",
            'a' => "Nos crèches sont ouvertes du lundi au vendredi, de 7h30 à 18h30. Des accueils flexibles sont possibles selon vos besoins.",
        ],
        [
            'q' => "Comment sont calculés les tarifs\u{00A0}?",
            'a' => "Les tarifs dépendent de votre quotient familial CAF et du nombre d'heures d'accueil souhaitées. Un crédit d'impôt de 50% s'applique. Nous établissons un devis personnalisé après votre demande.",
        ],
        [
            'q' => "Y a-t-il une période d'adaptation\u{00A0}?",
            'a' => "Oui, nous proposons une adaptation progressive sur une à deux semaines, selon le rythme de votre enfant et votre disponibilité.",
        ],
        [
            'q' => "Les repas sont-ils inclus\u{00A0}?",
            'a' => "Oui. Les repas et goûters sont préparés sur place chaque jour avec des produits frais et 100% bio, adaptés à l'âge et aux régimes spécifiques.",
        ],
        [
            'q' => "Comment se déroule l'inscription\u{00A0}?",
            'a' => "Téléchargez la fiche de préinscription depuis la page Contact, remplissez-la et renvoyez-la nous. Nous vous recontactons sous 48h pour organiser une visite.",
        ],
    ];
@endphp

<section class="section faq-section">
    <div class="container">
        <div class="faq-grid">
            <div>
                <span class="eyebrow">Questions fréquentes</span>
                <h2 class="mb-5">Tout ce que vous voulez savoir, <em class="italic-accent">avant la visite</em>.</h2>
                <p class="lede">
                    Vous ne trouvez pas la réponse à votre question&nbsp;? Contactez-nous, nous
                    vous répondrons sous 48h.
                </p>
                <a href="{{ route('contact.index') }}" class="btn btn-secondary mt-5">Nous contacter</a>
            </div>

            <div class="faq-list">
                @foreach ($faqs as $faq)
                    <details class="faq-item" @if(! empty($faq['open'])) open @endif>
                        <summary>{{ $faq['q'] }}</summary>
                        <p>{{ $faq['a'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </div>
</section>
