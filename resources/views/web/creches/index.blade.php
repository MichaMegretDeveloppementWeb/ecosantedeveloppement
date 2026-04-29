@extends('layouts.web')

@section('title', 'Nos crèches')
@section('subtitle', config('eco-sante.organization.name'))
@section('description', "Découvrez nos trois micro-crèches\u{00A0}: deux dans le Val d'Oise, une en Savoie. Toutes liées par le même projet pédagogique.")
@section('page', 'creches')

@push('assets')
    @vite(['resources/css/web/creches/index.css'])
@endpush

@push('schema')
    @php
        $crechesList = config('eco-sante.creches', []);
        $crechesUrl = route('creches.index');

        $itemListElements = [];
        $position = 1;
        foreach ($crechesList as $c) {
            $itemListElements[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'item' => [
                    '@type' => 'ChildCare',
                    'name' => "Micro-crèche {$c['name']}",
                    'url' => $crechesUrl . '#' . $c['slug'],
                    'address' => [
                        '@type' => 'PostalAddress',
                        'streetAddress' => $c['address'],
                        'postalCode' => $c['postal_code'],
                        'addressLocality' => $c['city'],
                        'addressCountry' => 'FR',
                    ],
                ],
            ];
        }

        $itemList = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Nos micro-crèches',
            'itemListElement' => $itemListElements,
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($itemList, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>

    <x-seo.breadcrumb-schema :items="[
        ['name' => 'Accueil', 'url' => route('home')],
        ['name' => 'Nos crèches', 'url' => route('creches.index')],
    ]" />
@endpush

@section('content')
    @include('web.creches.partials.header')
    @include('web.creches.partials.detail', ['creche' => config('eco-sante.creches.amel-adam'),    'reverse' => false, 'alt' => false])
    @include('web.creches.partials.detail', ['creche' => config('eco-sante.creches.bea-benoit'),   'reverse' => true,  'alt' => true])
    @include('web.creches.partials.detail', ['creche' => config('eco-sante.creches.chiara-hugo'),  'reverse' => false, 'alt' => false])
    @include('web.creches.partials.commun')
    @include('web.creches.partials.cta')
@endsection
