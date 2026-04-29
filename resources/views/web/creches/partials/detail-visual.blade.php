@php
    /** @var array $creche */
    /** @var string $palette */
    $satellites = $creche['satellite_illus'];
@endphp

<div class="structure-visual structure-visual-{{ $palette }}">
    <div class="structure-visual-blob"></div>
    <x-dynamic-component :component="'illu.' . $creche['main_illu']" class="structure-visual-illu structure-visual-main" />
    <x-dynamic-component :component="'illu.' . $satellites[0]" class="structure-visual-illu structure-visual-sm structure-visual-1" />
    <x-dynamic-component :component="'illu.' . $satellites[1]" class="structure-visual-illu structure-visual-sm structure-visual-2" />
    <x-dynamic-component :component="'illu.' . $satellites[2]" class="structure-visual-illu structure-visual-sm structure-visual-3" />
    <div class="structure-badge"><strong>{{ $creche['capacity'] }}</strong> berceaux</div>
</div>
