@php
    /** @var array $creche */
    /** @var string $palette */
    $satellites = $creche['satellite_illus'];
@endphp

<div class="structure-visual structure-visual-{{ $palette }}">
    {{-- Photo de la crèche dans une forme organique. Le border-radius est
         défini par palette dans le CSS pour donner à chaque crèche une
         silhouette unique. --}}
    <div class="structure-visual-photo">
        <img src="{{ asset($creche['photo']) }}" alt="Vue de la micro-crèche {{ $creche['name'] }}" loading="lazy">
    </div>

    {{-- Satellites illustrés en surimpression, conservés pour garder l'âme du design. --}}
    <x-dynamic-component :component="'illu.' . $satellites[0]" class="structure-visual-illu structure-visual-sm structure-visual-1" />
    <x-dynamic-component :component="'illu.' . $satellites[1]" class="structure-visual-illu structure-visual-sm structure-visual-2" />
    <x-dynamic-component :component="'illu.' . $satellites[2]" class="structure-visual-illu structure-visual-sm structure-visual-3" />
    <div class="structure-badge"><strong>{{ $creche['capacity'] }}</strong> berceaux</div>
</div>

{{--
    === VERSION PRÉCÉDENTE — illustration principale seule (sans photo) ===
    Pour revenir à l'illustration centrée à la place de la photo, décommenter
    le bloc ci-dessous et supprimer le <div class="structure-visual-photo">
    plus haut.

    <div class="structure-visual structure-visual-{{ $palette }}">
        <div class="structure-visual-blob"></div>
        <x-dynamic-component :component="'illu.' . $creche['main_illu']" class="structure-visual-illu structure-visual-main" />
        <x-dynamic-component :component="'illu.' . $satellites[0]" class="structure-visual-illu structure-visual-sm structure-visual-1" />
        <x-dynamic-component :component="'illu.' . $satellites[1]" class="structure-visual-illu structure-visual-sm structure-visual-2" />
        <x-dynamic-component :component="'illu.' . $satellites[2]" class="structure-visual-illu structure-visual-sm structure-visual-3" />
        <div class="structure-badge"><strong>{{ $creche['capacity'] }}</strong> berceaux</div>
    </div>
--}}
