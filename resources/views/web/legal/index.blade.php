@extends('layouts.web')

@section('title', 'Mentions légales')
@section('subtitle', config('eco-sante.organization.name'))
@section('description', 'Mentions légales du site Éco Santé Développement : éditeur, hébergement, propriété intellectuelle, données personnelles, RGPD et politique cookies.')
@section('page', 'legal')

@push('assets')
    @vite(['resources/css/web/legal/index.css'])
@endpush

@push('schema')
    @php
        $webPage = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => 'Mentions légales · ' . config('eco-sante.organization.name'),
            'description' => 'Mentions légales du site Éco Santé Développement : éditeur, hébergement, propriété intellectuelle, données personnelles, RGPD et politique cookies.',
            'url' => route('legal.index'),
            'inLanguage' => 'fr-FR',
            'isPartOf' => ['@id' => url('/') . '#website'],
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($webPage, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>

    <x-seo.breadcrumb-schema :items="[
        ['name' => 'Accueil', 'url' => route('home')],
        ['name' => 'Mentions légales', 'url' => route('legal.index')],
    ]" />
@endpush

@php
    $org = config('eco-sante.organization');
    $legal = config('eco-sante.legal');
    $creches = config('eco-sante.creches');
@endphp

@section('content')
<section class="container legal-page">
    <span class="eyebrow">Informations légales</span>
    <h1>Mentions légales.</h1>
    <p class="lede">
        Conformément à la loi pour la confiance dans l'économie numérique
        (LCEN), voici les informations légales relatives à ce site.
    </p>

    <nav class="legal-toc">
        <strong>Sommaire</strong>
        <ol>
            <li><a href="#editeur">Éditeur du site</a></li>
            <li><a href="#hebergeur">Hébergement</a></li>
            <li><a href="#propriete">Propriété intellectuelle</a></li>
            <li><a href="#donnees">Données personnelles &amp; RGPD</a></li>
            <li><a href="#cookies">Cookies</a></li>
            <li><a href="#responsabilite">Responsabilité</a></li>
        </ol>
    </nav>

    <h2 id="editeur">1. Éditeur du site</h2>
    <p>
        Le site <strong>{{ $legal['site_url'] }}</strong> est édité par
        <strong>{{ $org['name'] }}</strong>, exploitant trois micro-crèches privées&nbsp;:
    </p>
    <ul>
        @foreach ($creches as $creche)
            <li>
                Micro-crèche <strong>{{ $creche['name'] }}</strong>,
                {{ $creche['address'] }}, {{ $creche['postal_code'] }} {{ $creche['city'] }}
            </li>
        @endforeach
    </ul>
    <p>
        <strong>Téléphone&nbsp;:</strong> {{ $org['phone'] }}<br>
        <strong>Courriel&nbsp;:</strong> <a href="mailto:{{ $org['email'] }}">{{ $org['email'] }}</a><br>
        <strong>Directeur de la publication&nbsp;:</strong> {{ $legal['publication_director'] }}<br>
        <strong>SIRET&nbsp;:</strong> {{ $legal['siret'] }}<br>
        <strong>N° d'agrément PMI&nbsp;:</strong> {{ $legal['pmi_agreement_number'] }}
    </p>

    <h2 id="hebergeur">2. Hébergement</h2>
    <p>
        Le site est hébergé par <strong>{{ $legal['host']['name'] }}</strong>.
        {{ $legal['host']['address'] }}.
        Site web&nbsp;: <a href="{{ $legal['host']['website'] }}" target="_blank" rel="noopener">{{ $legal['host']['website'] }}</a>.
    </p>

    <h2 id="propriete">3. Propriété intellectuelle</h2>
    <p>
        L'ensemble des éléments présents sur ce site (textes, illustrations,
        photographies, logos, graphismes, structure du site) sont la propriété
        exclusive de {{ $org['name'] }} ou font l'objet d'une autorisation
        d'utilisation.
    </p>
    <p>
        Toute reproduction, représentation, modification ou exploitation, totale ou
        partielle, sans autorisation préalable écrite est strictement interdite et
        constitue une contrefaçon sanctionnée par les articles L.335-2 et suivants
        du Code de la propriété intellectuelle.
    </p>

    <h2 id="donnees">4. Données personnelles &amp; RGPD</h2>
    <p>
        Les informations recueillies via le formulaire de contact font l'objet d'un
        traitement informatique destiné à répondre à votre demande de pré-inscription,
        de visite ou d'information.
    </p>
    <p>
        <strong>Responsable du traitement&nbsp;:</strong> {{ $org['name'] }}.<br>
        <strong>Finalité&nbsp;:</strong> traitement des demandes de contact et
        d'inscription en crèche.<br>
        <strong>Durée de conservation&nbsp;:</strong> les données sont conservées
        pendant la durée strictement nécessaire au traitement de votre demande, et
        au maximum 3 ans à compter du dernier contact.<br>
        <strong>Destinataires&nbsp;:</strong> les données sont destinées
        exclusivement à l'équipe de {{ $org['name'] }} et ne sont jamais
        transmises à des tiers à des fins commerciales.
    </p>
    <p>
        Conformément au Règlement Général sur la Protection des Données (RGPD) et à
        la loi Informatique et Libertés, vous disposez d'un droit d'accès, de
        rectification, d'effacement, de portabilité, de limitation et d'opposition
        sur vos données personnelles. Vous pouvez exercer ces droits en nous
        contactant à <a href="mailto:{{ $org['email'] }}">{{ $org['email'] }}</a>.
    </p>
    <p>
        Vous pouvez également introduire une réclamation auprès de la CNIL
        (<a href="https://www.cnil.fr" target="_blank" rel="noopener">www.cnil.fr</a>) si vous estimez que vos
        droits ne sont pas respectés.
    </p>

    <h2 id="cookies">5. Cookies</h2>
    <p>
        Ce site n'utilise pas de cookies de mesure d'audience ni de cookies
        publicitaires. Seuls des cookies techniques strictement nécessaires au bon
        fonctionnement du site peuvent être déposés. Aucun consentement préalable
        n'est requis pour ces cookies essentiels.
    </p>

    <h2 id="responsabilite">6. Responsabilité</h2>
    <p>
        {{ $org['name'] }} s'efforce d'assurer l'exactitude et la mise à jour
        des informations diffusées sur ce site. Toutefois, l'éditeur ne peut
        garantir l'exactitude, la précision ou l'exhaustivité des informations
        mises à disposition.
    </p>
    <p>
        Les informations relatives aux tarifs, capacités et modalités d'accueil
        sont données à titre indicatif et peuvent évoluer. Seules les informations
        confirmées par écrit lors d'un échange direct avec notre équipe ont valeur
        contractuelle.
    </p>
    <p class="legal-updated">
        Dernière mise à jour&nbsp;: {{ $legal['last_updated_label'] }}.
    </p>
</section>
@endsection
