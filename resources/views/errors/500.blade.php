@extends('layouts.web')

@section('title', 'Une erreur est survenue')
@section('subtitle', config('eco-sante.organization.name'))
@section('description', 'Une erreur inattendue est survenue de notre côté.')
@section('page', 'error')

@push('assets')
    @vite(['resources/css/errors/index.css'])
@endpush

@section('content')
    @include('errors.partials.template', [
        'code'  => '500',
        'title' => 'Petit pépin de notre côté.',
        'lede'  => 'Une erreur inattendue est survenue. Notre équipe en est informée. Réessayez dans quelques instants ou revenez à l\'accueil.',
        'illu'  => 'nuage',
    ])
@endsection
