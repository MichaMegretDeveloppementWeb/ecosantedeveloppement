@php
    $creches = config('eco-sante.creches');
@endphp

<section class="page-header">
    <div class="page-header-blob page-header-blob-1"></div>
    <div class="page-header-blob page-header-blob-2"></div>
    <div class="container">
        <span class="eyebrow">Nos structures</span>
        <h1>Trois maisons,<br><em class="italic-accent">une même attention</em>.</h1>
        <p class="lede">
            Deux crèches dans le Val d'Oise, une en Savoie. Chacune avec son ambiance,
            son équipe, son quartier, toutes liées par le même projet pédagogique.
        </p>
        <div class="quick-nav">
            @foreach ($creches as $creche)
                <a href="#{{ $creche['slug'] }}">{{ $creche['name'] }}</a>
            @endforeach
        </div>
    </div>
</section>
