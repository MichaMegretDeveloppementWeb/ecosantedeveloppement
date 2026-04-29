@extends('layouts.web')

@section('title', 'Nous contacter')
@section('subtitle', config('eco-sante.organization.name'))
@section('description', "Une question sur nos micro-crèches, une demande de visite ou de préinscription\u{00A0}? Nous vous répondons sous 48h, par téléphone, courriel ou via notre formulaire.")
@section('page', 'contact')

@push('assets')
    @vite(['resources/css/web/contact/index.css', 'resources/js/web/contact/index.js'])
@endpush

@push('schema')
    @php
        $contactPage = [
            '@context' => 'https://schema.org',
            '@type' => 'ContactPage',
            'name' => 'Contact · ' . config('eco-sante.organization.name'),
            'description' => "Une question sur nos micro-crèches, une demande de visite ou de préinscription\u{00A0}? Nous vous répondons sous 48h, par téléphone, courriel ou via notre formulaire.",
            'url' => route('contact.index'),
            'inLanguage' => 'fr-FR',
            'isPartOf' => ['@id' => url('/') . '#website'],
            'about' => ['@id' => url('/') . '#organization'],
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($contactPage, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>

    <x-seo.breadcrumb-schema :items="[
        ['name' => 'Accueil', 'url' => route('home')],
        ['name' => 'Contact', 'url' => route('contact.index')],
    ]" />
@endpush

@section('content')
    @include('web.contact.partials.header')

    <section class="section contact-section">
        <div class="container">
            <div class="contact-grid">
                @include('web.contact.partials.sidebar')

                <livewire:web.contact-form />

            </div>
        </div>
    </section>
@endsection
