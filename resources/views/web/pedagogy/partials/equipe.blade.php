@php
    $stats = config('eco-sante.organization.stats');
@endphp

<section class="section equipe-section" id="equipe">
    <div class="container">
        <div class="equipe-grid">
            <div class="equipe-visual">
                <div class="equipe-blob"></div>
                <x-illu.enfant class="equipe-illu equipe-illu-1" />
                <x-illu.livre  class="equipe-illu equipe-illu-2" />
                <x-illu.ballon class="equipe-illu equipe-illu-3" />
                <x-illu.blocs  class="equipe-illu equipe-illu-4" />
            </div>
            <div>
                <span class="eyebrow">Une équipe qualifiée &amp; engagée</span>
                <h2 class="mb-5">Des professionnelles<br><em class="italic-accent">au quotidien</em>.</h2>
                <p class="lede mb-5">
                    Nos professionnels de la petite enfance, tous diplômés, accompagnent
                    chaque enfant avec bienveillance, respect et professionnalisme.
                </p>
                <p class="lede mb-5">
                    La stabilité de l'équipe garantit aux enfants des repères sécurisants,
                    des visages familiers, des liens qui se tissent dans la durée.
                </p>
                <div class="equipe-stats">
                    <div>
                        <strong>{{ $stats['staff_count'] }}</strong>
                        <span>professionnelles</span>
                    </div>
                    <div>
                        <strong>{{ $stats['qualified_percent'] }}%</strong>
                        <span>diplômées petite enfance</span>
                    </div>
                    <div>
                        <strong>{{ $stats['adult_child_ratio'] }}</strong>
                        <span>ratio adulte&nbsp;/&nbsp;enfant</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
