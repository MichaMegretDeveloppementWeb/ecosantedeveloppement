@extends('layouts.web')

@section('title', config('eco-sante.organization.name'))
@section('subtitle', 'Trois micro-crèches privées')
@section('description', 'Éco Santé Développement réunit trois micro-crèches privées qui accueillent les tout-petits dans un cadre chaleureux, bienveillant et stimulant.')
@section('page', 'home')

@push('assets')
    @vite(['resources/css/web/home/index.css'])
@endpush

@push('schema')
    @php
        $webSite = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => url('/') . '#website',
            'url' => url('/'),
            'name' => config('eco-sante.organization.name'),
            'description' => config('eco-sante.organization.tagline'),
            'inLanguage' => 'fr-FR',
            'publisher' => ['@id' => url('/') . '#organization'],
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($webSite, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    @include('web.home.partials.hero')
    @include('web.home.partials.intro')
    @include('web.home.partials.structures')
    @include('web.home.partials.valeurs')
    @include('web.home.partials.journee')
    @include('web.home.partials.temoignages')
    @include('web.home.partials.faq')
    @include('web.home.partials.cta')
@endsection
