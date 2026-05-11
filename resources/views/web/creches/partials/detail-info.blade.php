@php
    /** @var array $creche */
    /** @var string $palette */
    /** @var string $pillClass */
@endphp

<div class="structure-info">
    <span class="{{ $pillClass }}">{{ $creche['department'] }} · {{ $creche['department_code'] }}</span>
    <h2>Micro-crèche {{ $creche['name'] }}</h2>

    <div class="info-grid">
        <div>
            <span class="info-label">Adresse</span>
            <strong>{{ $creche['address'] }}<br>{{ $creche['postal_code'] }} {{ $creche['city'] }}</strong>
        </div>
        <div>
            <span class="info-label">Capacité d'accueil</span>
            <strong>{{ $creche['capacity'] }} enfants</strong>
        </div>
    </div>
</div>
