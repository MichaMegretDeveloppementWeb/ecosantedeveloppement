@extends('layouts.web')

@section('title', 'Trop de requêtes')
@section('subtitle', config('eco-sante.organization.name'))
@section('description', 'Vous avez fait trop de requêtes en peu de temps.')
@section('page', 'error')

@push('assets')
    @vite(['resources/css/errors/index.css'])
@endpush

@section('content')
    @include('errors.partials.template', [
        'code'  => '429',
        'title' => 'Tout doux.',
        'lede'  => 'Vous avez fait trop de requêtes en peu de temps. Patientez quelques instants avant de réessayer.',
        'illu'  => 'feuille',
    ])
@endsection
