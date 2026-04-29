@extends('layouts.web')

@section('title', 'Session expirée')
@section('subtitle', config('eco-sante.organization.name'))
@section('description', 'Votre session a expiré.')
@section('page', 'error')

@push('assets')
    @vite(['resources/css/errors/index.css'])
@endpush

@section('content')
    @include('errors.partials.template', [
        'code'  => '419',
        'title' => 'Votre session a expiré.',
        'lede'  => 'Pour des raisons de sécurité, votre session a été interrompue. Rechargez la page pour continuer votre demande.',
        'illu'  => 'soleil',
    ])
@endsection
