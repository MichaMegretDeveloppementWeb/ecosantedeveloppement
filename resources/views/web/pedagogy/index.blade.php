@extends('layouts.web')

@section('title', 'Projet pédagogique')
@section('subtitle', config('eco-sante.organization.name'))
@section('description', "Notre projet pédagogique\u{00A0}: un cadre de vie qui favorise le bien-être, la santé et le développement global de l'enfant, à son rythme.")
@section('page', 'pedagogy')

@push('assets')
    @vite(['resources/css/web/pedagogy/index.css'])
@endpush

@push('schema')
    @php
        $aboutPage = [
            '@context' => 'https://schema.org',
            '@type' => 'AboutPage',
            'name' => 'Projet pédagogique · ' . config('eco-sante.organization.name'),
            'description' => "Notre projet pédagogique\u{00A0}: un cadre de vie qui favorise le bien-être, la santé et le développement global de l'enfant, à son rythme.",
            'url' => route('pedagogy.index'),
            'inLanguage' => 'fr-FR',
            'isPartOf' => ['@id' => url('/') . '#website'],
            'about' => ['@id' => url('/') . '#organization'],
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($aboutPage, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>

    <x-seo.breadcrumb-schema :items="[
        ['name' => 'Accueil', 'url' => route('home')],
        ['name' => 'Projet pédagogique', 'url' => route('pedagogy.index')],
    ]" />
@endpush

@section('content')
    @include('web.pedagogy.partials.header')
    @include('web.pedagogy.partials.promesses')
    @include('web.pedagogy.partials.orientations')
    @include('web.pedagogy.partials.journee')
    @include('web.pedagogy.partials.equipe')
    @include('web.pedagogy.partials.bien-etre')
    @include('web.pedagogy.partials.pourquoi')
    @include('web.pedagogy.partials.cta')
@endsection
