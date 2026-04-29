@php
    /** @var array $creche */
    /** @var string $palette */
    /** @var string $pillClass */
@endphp

<div class="structure-info">
    <span class="{{ $pillClass }}">{{ $creche['department'] }} · {{ $creche['department_code'] }}</span>
    <h2>{{ $creche['name'] }}</h2>
    <p class="lede">{{ $creche['lede'] }}</p>

    <div class="info-grid">
        <div>
            <span class="info-label">Adresse</span>
            <strong>{{ $creche['address'] }}<br>{{ $creche['postal_code'] }} {{ $creche['city'] }}</strong>
        </div>
        <div>
            <span class="info-label">Capacité</span>
            <strong>{{ $creche['capacity'] }} enfants</strong>
            <span class="info-sub">de 10 semaines à 3 ans</span>
        </div>
        <div>
            <span class="info-label">Horaires</span>
            <strong>{{ config('eco-sante.organization.opening_hours.hours') }}</strong>
            <span class="info-sub">du lundi au vendredi</span>
        </div>
        <div>
            <span class="info-label">Équipe</span>
            <strong>{{ $creche['staff_count'] }} professionnelles</strong>
            <span class="info-sub">de la petite enfance</span>
        </div>
    </div>

    <h4 class="mt-6 mb-3">Ce que les enfants y trouvent</h4>
    <ul class="feature-list">
        @foreach ($creche['features'] as $feature)
            <li><span class="dot dot-{{ $palette }}"></span>{{ $feature }}</li>
        @endforeach
    </ul>

    <div class="flex gap-3 mt-6" style="flex-wrap:wrap;">
        <a href="{{ route('contact.index') }}?creche={{ $creche['slug'] }}" class="btn btn-primary">Préinscrire mon enfant</a>
        <a href="{{ route('contact.index') }}" class="btn btn-secondary">Visiter la crèche</a>
    </div>
</div>
