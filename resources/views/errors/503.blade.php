@extends('layouts.web')

@section('title', 'Site en maintenance')
@section('subtitle', config('eco-sante.organization.name'))
@section('description', 'Le site est temporairement en maintenance.')
@section('page', 'error')

@push('assets')
    @vite(['resources/css/errors/index.css'])
@endpush

@section('content')
    @include('errors.partials.template', [
        'code'  => '503',
        'title' => 'Sieste en cours.',
        'lede'  => 'Le site est en maintenance pour quelques instants. Merci de revenir un peu plus tard. Pour toute urgence, contactez-nous par téléphone.',
        'illu'  => 'fleur',
    ])
@endsection
