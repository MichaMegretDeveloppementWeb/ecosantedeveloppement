@extends('layouts.web')

@section('title', 'Accès refusé')
@section('subtitle', config('eco-sante.organization.name'))
@section('description', "Vous n'avez pas accès à cette page.")
@section('page', 'error')

@push('assets')
    @vite(['resources/css/errors/index.css'])
@endpush

@section('content')
    @include('errors.partials.template', [
        'code'  => '403',
        'title' => 'Cette page est réservée.',
        'lede'  => "Vous n'avez pas l'autorisation d'accéder à cette page. Revenez à l'accueil pour continuer votre visite.",
        'illu'  => 'maison',
    ])
@endsection
