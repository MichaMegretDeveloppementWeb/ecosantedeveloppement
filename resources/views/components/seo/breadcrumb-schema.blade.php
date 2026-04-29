@props([
    /** @var array<int, array{name: string, url: string}> Liste ordonnée des items du fil d'Ariane. */
    'items' => [],
])

@php
    $itemListElement = [];
    foreach ($items as $index => $item) {
        $itemListElement[] = [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $item['name'],
            'item' => $item['url'],
        ];
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $itemListElement,
    ];
@endphp

<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
