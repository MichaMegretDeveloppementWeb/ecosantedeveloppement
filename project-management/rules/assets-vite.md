# Gestion des assets avec Vite

## Principe

Ce document decrit comment **segmenter les assets CSS et JS par zone et par page** quand le projet en a besoin. Il s'applique a toutes les zones fonctionnelles du projet (→ voir `rules/structure-fichiers.md` pour la segmentation par zone).

**Important :** Cette architecture de segmentation n'est pas toujours necessaire. Son utilite depend des choix techniques du projet :

| Situation | Approche assets |
|-----------|----------------|
| Tailwind CSS en classes inline uniquement | Un seul fichier CSS global par zone suffit — pas besoin de fichiers CSS par page |
| Tailwind CSS avec des classes custom declarees dans des fichiers `.css` (`@apply`, `@layer`, etc.) | Cette segmentation par zone/page est pertinente pour organiser ces fichiers |
| CSS custom (sans Tailwind ou en complement) | Cette segmentation est recommandee |
| JavaScript specifique a certaines pages | Cette segmentation est recommandee pour le JS |

Le but est de definir **ou placer les fichiers** quand ils existent — pas d'imposer leur existence.

---

## Points d'entree globaux par zone

Le projet utilise des points d'entree CSS/JS distincts par zone fonctionnelle :

| Zone | Layout | CSS | JS | Role |
|------|--------|-----|-----|------|
| Public | `layouts/web.blade.php` | `web.css` | `web.js` | Tailwind + tokens VantaDrive + Alpine standalone |
| Auth | `layouts/auth.blade.php` | `ui-kit.css` | `ui-kit.js` + `auth.js` | UI Kit + Alpine standalone |
| Back-office | `layouts/app.blade.php` | `ui-kit.css` + `app.css` | `ui-kit.js` + `app.js` | UI Kit + styles custom + Alpine standalone |

### Alpine.js et Livewire

Alpine.js est charge en standalone dans chaque zone via son fichier JS (`web.js`, `auth.js`, `app.js`). Le chargement est conditionnel :

```js
import Alpine from 'alpinejs';
if (!window.Alpine) {
    window.Alpine = Alpine;
    Alpine.start();
}
```

Livewire 4 embarque sa propre version d'Alpine et l'injecte automatiquement quand un composant Livewire est present sur la page. Le guard `if (!window.Alpine)` empeche la double initialisation. Sur les pages sans composant Livewire, Alpine est initialise par le fichier JS de la zone.

### Regles

- `web.css` : contient `@import 'tailwindcss'` + tokens VantaDrive. Uniquement pour les pages publiques.
- `ui-kit.css` : contient `@import 'tailwindcss'` + preset Falcon UI Kit. Pour les pages auth et back-office.
- **Ne jamais charger `web.css` et `ui-kit.css` sur la meme page** (double Tailwind, conflits de tokens).
- `app.css` : styles back-office custom. **Pas de `@import 'tailwindcss'`** (deja dans `ui-kit.css`).

---

## Structure des fichiers

```
resources/
├── css/
│   ├── web.css                          # Point d'entree CSS public (Tailwind + VantaDrive)
│   ├── app.css                          # Point d'entree CSS back-office (styles custom, sans Tailwind)
│   ├── ui-kit.css                       # Point d'entree CSS UI Kit (Tailwind + Falcon)
│   ├── components/
│   │   └── layout/
│   │       ├── header.css               # Styles du header public (si necessaire)
│   │       └── footer.css               # Styles du footer public (si necessaire)
│   └── {zone}/                          # Un dossier par zone fonctionnelle
│       ├── {page}/
│       │   ├── index.css                # Point d'entree CSS de la page
│       │   ├── {section}.css            # Styles d'une section (optionnel)
│       │   └── {section}.css            # Styles d'une autre section (optionnel)
│       └── {autre-page}/
│           └── index.css
└── js/
    ├── web.js                           # Point d'entree JS public (Alpine + bootstrap)
    ├── auth.js                          # Point d'entree JS auth (Alpine)
    ├── app.js                           # Point d'entree JS back-office (Alpine + bootstrap)
    ├── ui-kit.js                        # Point d'entree JS UI Kit (theme, Chart.js)
    ├── bootstrap.js                     # Setup commun (axios)
    ├── components/
    │   └── layout/
    │       ├── header.js
    │       └── footer.js
    └── {zone}/
        ├── {page}/
        │   ├── index.js                 # Point d'entree JS de la page
        │   └── {section}.js             # JS d'une section (optionnel)
        └── {autre-page}/
            └── index.js
```

**Exemple concret** (a titre d'illustration) :

```
resources/css/
├── web.css
└── web/
    ├── home/
    │   ├── index.css
    │   └── hero.css
    └── contact/
        └── index.css

resources/js/
└── web/
    └── home/
        ├── index.js
        └── hero.js
```

---

## Regles

### Un fichier `index.css` / `index.js` par page (quand necessaire)

Quand une page a besoin de CSS ou JS specifique, elle doit avoir un fichier `index.css` et/ou `index.js` dans son dossier sous `resources/css/{zone}/{page}/` ou `resources/js/{zone}/{page}/`. Ce fichier est le **point d'entree unique** de la page pour Vite.

### Segmentation optionnelle par section

Si le CSS ou le JS d'une page devient trop volumineux, le diviser en fichiers dedies a chaque section. Ces fichiers sont importes dans le `index.css` ou `index.js` correspondant :

```css
/* resources/css/{zone}/{page}/index.css */
@import './hero.css';
@import './features.css';
```

```js
// resources/js/{zone}/{page}/index.js
import './hero.js';
import './slider.js';
```

### Assets du layout (composants partages)

Les fichiers CSS et JS des composants du layout (header, footer) se trouvent dans `resources/css/components/layout/` et `resources/js/components/layout/`. Ils sont importes dans le layout car ils sont communs a toutes les pages qui l'utilisent :

```blade
{{-- resources/views/layouts/{nom}.blade.php --}}
<head>
    @vite([
        'resources/css/components/layout/header.css',
        'resources/js/components/layout/header.js',
        'resources/css/components/layout/footer.css',
        'resources/js/components/layout/footer.js',
    ])
</head>
```

### Directive `@vite` dans les vues de page

Chaque vue qui a des assets specifiques inclut une directive `@vite` dans la section `assets` prevue par le layout :

```blade
@extends('layouts.{nom}')

@section('assets')
    @vite([
        'resources/css/{zone}/{page}/index.css',
        'resources/js/{zone}/{page}/index.js',
    ])
@endsection
```

Le layout doit prevoir cette section dans le `<head>` :

```blade
<head>
    {{-- Assets globaux de la zone --}}
    @vite(['resources/css/web.css', 'resources/js/web.js'])

    {{-- Assets specifiques a la page (optionnel) --}}
    @yield('assets')
</head>
```

---

## Configuration de Vite

Le fichier `vite.config.js` peut utiliser `glob` pour detecter automatiquement tous les points d'entree par zone. Adapter les patterns glob aux zones du projet :

```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { glob } from 'glob';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Public (web) entry points
                'resources/css/web.css',
                'resources/js/web.js',
                // Auth entry point
                'resources/js/auth.js',
                // Back-office (app) entry points
                'resources/css/app.css',
                'resources/js/app.js',
                // UI Kit entry points
                'resources/css/ui-kit.css',
                'resources/js/ui-kit.js',
                // Points d'entree par page — adapter les patterns aux zones du projet
                ...glob.sync('resources/css/*/*/index.css'),
                ...glob.sync('resources/js/*/*/index.js'),
                // Composants layout
                ...glob.sync('resources/css/components/layout/*.css'),
                ...glob.sync('resources/js/components/layout/*.js'),
            ],
            refresh: true,
        }),
    ],
});
```

Chaque nouveau dossier de page avec un `index.css` / `index.js` sera automatiquement pris en charge sans modifier la configuration.

---

## Quand cette segmentation ne s'applique pas

Si le projet utilise exclusivement Tailwind CSS en classes inline et n'a pas de JavaScript specifique par page, un seul point d'entree global par zone suffit :

```
resources/
├── css/web.css      # Tailwind + tokens publics
├── css/app.css      # Styles custom back-office
└── js/web.js        # Alpine + bootstrap
```

La segmentation par zone et par page decrite dans ce document n'est utile que lorsque des fichiers CSS ou JS specifiques a une page ou une zone existent effectivement. Elle definit **comment les organiser**, pas **quand les creer**.

---

## Images statiques

### Organisation

Les images statiques se placent dans `public/images/`, organisees par contexte :

```
public/images/
├── logo.svg                    # Logo principal (SVG, currentColor)
├── favicons/                   # Favicons et manifeste PWA
├── auth/                       # Images des pages d'authentification
│   └── auth-bg.webp
└── web/                        # Images des pages publiques
    ├── home/                   # Images de la homepage
    │   └── hero-bg.webp
    └── {page}/                 # Images d'une page specifique
```

### Convention de placeholder avec prompt AI

Quand une page necessite une image d'ambiance ou decorative, on utilise un **placeholder provisoire** accompagne d'un **prompt AI detaille** en commentaire. Ce pattern permet au designer/developpeur de generer l'image optimale puis de l'integrer sans modifier le code.

**Format standard :**

```blade
{{-- ============================================================
     IMAGE: {description courte}
     Emplacement cible : public/images/{zone}/{nom}.webp
     Format : WebP, qualite 85%, {dimensions}px
     Prompt AI :
     {Prompt ultra-detaille pour generer l'image ideale.
      Inclure : sujet, style, eclairage, palette de couleurs,
      composition, angle de vue, ambiance, resolution.}
     ============================================================ --}}
{{-- <img src="{{ asset('images/{zone}/{nom}.webp') }}" alt="{description alt}" class="{classes Tailwind}" /> --}}

{{-- Placeholder provisoire --}}
<div class="{memes dimensions et position que l'image}" style="background: linear-gradient(...)"></div>
```

**Regles :**

1. **Le commentaire avec le prompt doit etre au-dessus** de la balise `<img>` commentee
2. **La balise `<img>` est prete a l'emploi** : attributs `src`, `alt`, et classes Tailwind deja definis — il suffit de la decommenter
3. **Le placeholder** reproduit les dimensions et le positionnement de l'image finale (gradient, couleur unie, ou SVG minimal)
4. **Format WebP** privilegie pour les photos (compression superieure, supporte par tous les navigateurs modernes)
5. **Nommage** : `{contexte}-{description}.webp` en kebab-case (ex: `hero-bg.webp`, `how-it-works-step-1.webp`)
6. **Le commentaire du prompt est supprime** une fois l'image importee et la balise decommentee

**Exemple reel** (auth layout) :

```blade
{{-- IMAGE: Background de la page d'authentification
     Emplacement : public/images/auth/auth-bg.webp
     Format : WebP, 1920x1080px
     Prompt AI : Luxury car on a Swiss alpine road at dusk... --}}
<img src="{{ asset('images/auth/auth-bg.webp') }}" alt="Voiture de luxe sur une route alpine suisse au crepuscule" class="absolute inset-0 h-full w-full object-cover" />
```

---

## Resume

| Element | Emplacement | Import |
|---------|-------------|--------|
| Point d'entree public | `css/web.css`, `js/web.js` | `@vite` dans `layouts/web.blade.php` |
| Point d'entree auth | `js/auth.js` | `@vite` dans `layouts/auth.blade.php` |
| Point d'entree back-office | `css/app.css`, `js/app.js` | `@vite` dans `layouts/app.blade.php` |
| Point d'entree UI Kit | `css/ui-kit.css`, `js/ui-kit.js` | `@vite` dans `layouts/auth.blade.php` et `layouts/app.blade.php` |
| Assets page | `css/{zone}/{page}/index.css`, `js/{zone}/{page}/index.js` | `@vite` dans la vue via `@yield('assets')` |
| Assets section (optionnel) | `css/{zone}/{page}/{section}.css`, `js/{zone}/{page}/{section}.js` | `@import` / `import` dans le fichier `index` parent |
| Assets composant layout | `css/components/layout/{composant}.css`, `js/components/layout/{composant}.js` | `@vite` dans le layout |
