@php
    /*
     * Organization JSON-LD : injecté globalement par le layout web.
     * Inclut les 3 micro-crèches en subOrganization (type ChildCare).
     * Référence : https://schema.org/Organization, https://schema.org/ChildCare
     */
    $org = config('eco-sante.organization');
    $creches = config('eco-sante.creches', []);

    // Téléphone au format E.164 (recommandé Google).
    $phoneE164 = '+33' . ltrim($org['phone_raw'], '0');

    $subOrganization = [];
    foreach ($creches as $creche) {
        $subOrganization[] = [
            '@type' => 'ChildCare',
            'name' => "Micro-crèche {$creche['name']}",
            'description' => $creche['lede'],
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $creche['address'],
                'postalCode' => $creche['postal_code'],
                'addressLocality' => $creche['city'],
                'addressRegion' => $creche['department'],
                'addressCountry' => 'FR',
            ],
            'telephone' => $phoneE164,
            'email' => $org['email'],
            // Format structuré recommandé par Google (plus précis que la chaîne « Mo-Fr 07:30-18:30 »).
            'openingHoursSpecification' => [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'opens' => '07:30',
                'closes' => '18:30',
            ],
            'url' => url(route('creches.index', absolute: false)) . '#' . $creche['slug'],
            'parentOrganization' => [
                '@type' => 'Organization',
                'name' => $org['name'],
            ],
        ];
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        '@id' => url('/') . '#organization',
        'name' => $org['name'],
        'url' => url('/'),
        'description' => $org['tagline'],
        'telephone' => $phoneE164,
        'email' => $org['email'],
        'areaServed' => [
            ['@type' => 'City', 'name' => 'Deuil-la-Barre'],
            ['@type' => 'City', 'name' => 'Bassens'],
        ],
        'subOrganization' => $subOrganization,
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'contactType' => 'customer service',
            'telephone' => $phoneE164,
            'email' => $org['email'],
            'availableLanguage' => ['fr'],
        ],
    ];
@endphp

<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
