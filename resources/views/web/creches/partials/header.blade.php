@php
    $creches = config('eco-sante.creches');
@endphp

<section class="page-header">
    <div class="page-header-blob page-header-blob-1"></div>
    <div class="page-header-blob page-header-blob-2"></div>
    <div class="container">
        <span class="eyebrow">Nos micro-crèches privées</span>
        <h1>Bienvenue dans un<br><em class="italic-accent">lieu pensé pour vos enfants</em>.</h1>
        <p class="lede">
            Nos trois micro-crèches privées accueillent les tout-petits dans un environnement
            chaleureux, sécurisé et adapté à leurs premiers apprentissages. Chaque structure
            est à taille humaine afin de garantir un accompagnement individualisé, respectueux
            du rythme et des besoins de chaque enfant.
        </p>
        <div class="quick-nav">
            @foreach ($creches as $creche)
                <a href="#{{ $creche['slug'] }}">{{ $creche['name'] }}</a>
            @endforeach
        </div>
    </div>
</section>
