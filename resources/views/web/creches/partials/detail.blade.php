@php
    /** @var array $creche */
    /** @var bool $reverse */
    /** @var bool $alt */
    $palette = $creche['palette']; // rose | jaune | bleu
    $pillClass = $palette === 'rose' ? 'pill' : 'pill pill-' . $palette;
@endphp

<section class="structure-detail @if($alt) structure-detail-alt @endif" id="{{ $creche['slug'] }}">
    <div class="container">
        <div class="structure-detail-grid @if($reverse) structure-detail-grid-rev @endif">
            @if ($reverse)
                @include('web.creches.partials.detail-info',   ['creche' => $creche, 'palette' => $palette, 'pillClass' => $pillClass])
                @include('web.creches.partials.detail-visual', ['creche' => $creche, 'palette' => $palette])
            @else
                @include('web.creches.partials.detail-visual', ['creche' => $creche, 'palette' => $palette])
                @include('web.creches.partials.detail-info',   ['creche' => $creche, 'palette' => $palette, 'pillClass' => $pillClass])
            @endif
        </div>
    </div>
</section>
