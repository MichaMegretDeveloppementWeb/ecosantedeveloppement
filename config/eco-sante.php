<?php

/*
|--------------------------------------------------------------------------
| Eco Santé Développement : données statiques du site
|--------------------------------------------------------------------------
|
| Ce fichier centralise toutes les informations factuelles affichées sur
| le site (coordonnées, adresses, slogans, mentions légales, statistiques).
| Il NE contient PAS de secrets, uniquement des données publiques.
|
| Pour modifier le site, il suffit de mettre à jour ce fichier puis de
| vider le cache de config : `php artisan config:clear`.
|
| Accès dans les vues : `config('eco-sante.organization.name')`.
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Organisme
    |--------------------------------------------------------------------------
    */
    'organization' => [
        'name' => 'Éco Santé Développement',
        'tagline' => 'Trois micro-crèches privées qui accueillent vos tout-petits dans un cadre chaleureux, bienveillant et stimulant.',
        'phone' => '06 66 84 16 69',
        'phone_raw' => '0666841669',           // pour href="tel:..."
        'email' => 'ecosantedeveloppement@orange.fr',
        'opening_hours' => [
            'days' => 'Lundi au vendredi',
            'hours' => '7h30 à 18h30',
        ],
        // Statistiques affichées sur la page d'accueil et le projet pédagogique
        'stats' => [
            'structures_count' => 3,
            'children_capacity' => 32,         // 10 + 10 + 12
            'organic_meals_percent' => 100,
            'staff_count' => 13,
            'qualified_percent' => 100,
            'adult_child_ratio' => '1 / 5',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Crèches
    |--------------------------------------------------------------------------
    | Le `slug` est utilisé comme ancre de page (#amel-adam) et comme valeur
    | de radio dans le formulaire de contact. Ne pas changer sans renommer
    | les ancres correspondantes dans les vues.
    */
    'creches' => [

        'amel-adam' => [
            'slug' => 'amel-adam',
            'name' => 'Amel & Adam',
            'address' => '3 rue d\'Ormesson',
            'postal_code' => '95170',
            'city' => 'Deuil-la-Barre',
            'department' => 'Val d\'Oise',
            'department_code' => '95',
            'capacity' => 10,
            'staff_count' => 4,
            'palette' => 'rose',
            'main_illu' => 'fleur',
            'satellite_illus' => ['papillon', 'coeur', 'etoile'],
            'lede' => 'Notre première micro-crèche, nichée au cœur du quartier d\'Ormesson à Deuil-la-Barre. Un cocon où dix petits explorent, créent et grandissent côte à côte.',
            'features' => [
                'Un grand espace de motricité libre lumineux',
                'Un coin lecture avec bibliothèque sensorielle',
                'Une cour extérieure aménagée pour les jeux d\'eau',
                'Cuisine ouverte pour préparer les repas bio devant les enfants',
            ],
        ],

        'bea-benoit' => [
            'slug' => 'bea-benoit',
            'name' => 'Béa & Benoit',
            'address' => '22 rue des Tilleuls',
            'postal_code' => '95170',
            'city' => 'Deuil-la-Barre',
            'department' => 'Val d\'Oise',
            'department_code' => '95',
            'capacity' => 10,
            'staff_count' => 4,
            'palette' => 'jaune',
            'main_illu' => 'soleil',
            'satellite_illus' => ['feuille', 'nuage', 'ballon'],
            'lede' => 'À deux pas du Lac Marchais, entre Deuil-la-Barre et Montmagny, notre deuxième micro-crèche profite d\'un environnement vert et calme, idéal pour les premiers pas en plein air.',
            'features' => [
                'Un jardin d\'éveil avec carré potager',
                'Sorties régulières au Lac Marchais',
                'Atelier musique animé chaque semaine',
                'Espace dortoir séparé, ambiance tamisée',
            ],
        ],

        'chiara-hugo' => [
            'slug' => 'chiara-hugo',
            'name' => 'Chiara & Hugo',
            'address' => '98 rue Arthur Hulotte',
            'postal_code' => '73000',
            'city' => 'Bassens',
            'department' => 'Savoie',
            'department_code' => '73',
            'capacity' => 12,
            'staff_count' => 5,
            'palette' => 'bleu',
            'main_illu' => 'papillon',
            'satellite_illus' => ['fleur', 'etoile', 'livre'],
            'lede' => 'Notre crèche savoyarde, à Bassens près de Chambéry. La plus grande des trois (12 enfants), avec un agencement pensé pour les balades en montagne et la découverte de la nature alentour.',
            'features' => [
                'Vue dégagée sur les montagnes depuis la salle de jeu',
                'Espace nature avec balades en poussette quotidiennes',
                'Salle de motricité agrandie avec parcours sensoriel',
                'Partenariat avec la médiathèque de Bassens',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Contact (destinataire des messages du formulaire)
    |--------------------------------------------------------------------------
    */
    'contact' => [
        // Email destinataire des soumissions du formulaire de contact.
        // Peut différer de l'email de contact public si besoin.
        'recipient_email' => 'ecosantedeveloppement@orange.fr',

        // Chemin (relatif au disque "public") du PDF téléchargeable.
        // Pour le mettre à jour : déposer le fichier dans storage/app/public/files/
        // puis exécuter `php artisan storage:link` une fois.
        // Le placeholder peut aussi être servi directement depuis public/files/.
        'preinscription_pdf_path' => 'files/fiche-preinscription.pdf',
    ],

    /*
    |--------------------------------------------------------------------------
    | Mentions légales
    |--------------------------------------------------------------------------
    | Les valeurs marquées TODO sont à compléter par le client.
    */
    'legal' => [
        'site_url' => 'ecosantedeveloppement.fr',
        'publication_director' => 'Ali CHOUKAL',
        'siret' => 'TODO',                                   // À compléter
        'pmi_agreement_number' => 'TODO',                                   // À compléter
        'host' => [
            'name' => 'Hostinger',
            'address' => 'Hostinger International Ltd., 61 Lordou Vironos Street, 6023 Larnaca, Chypre',
            'website' => 'https://www.hostinger.fr',
        ],
        'last_updated_label' => 'avril 2026',
    ],

];
