@extends('layouts.web')

@section('title', 'Page introuvable')
@section('subtitle', config('eco-sante.organization.name'))
@section('description', "La page que vous cherchez n'existe pas ou a été déplacée.")
@section('page', 'error')

@push('assets')
    @vite(['resources/css/errors/index.css'])
@endpush

@section('content')
    @include('errors.partials.template', [
        'code'  => '404',
        'title' => 'Cette page joue à cache-cache.',
        'lede'  => "La page que vous cherchez n'existe pas ou a été déplacée. Revenez à l'accueil ou contactez-nous si vous pensez qu'il s'agit d'une erreur.",
        'illu'  => 'papillon',
    ])
@endsection
