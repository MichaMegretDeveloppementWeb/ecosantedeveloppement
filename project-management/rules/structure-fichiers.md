# Structure des fichiers

## Principe

Le projet suit une architecture segmentee selon deux axes :

1. **Par zone fonctionnelle** pour la couche presentation (controllers, composants Livewire, vues) — chaque zone a son propre namespace.
2. **Par domaine metier** pour les couches metier (Actions, Services, Repositories, Exceptions) — partagees et independantes de la zone d'appel.

Cette double segmentation garantit que le code de presentation reste propre a chaque contexte d'utilisation, tandis que la logique metier est centralisee et reutilisable.

---

## Les deux axes de segmentation

### Axe 1 — Zones fonctionnelles (couche presentation)

Une zone fonctionnelle correspond a un contexte d'utilisation distinct de l'application (ex: site public, espace utilisateur, backoffice admin, API...). Chaque zone a ses propres controllers, composants Livewire et vues.

**Regle :** Chaque zone identifiee dans le projet doit avoir un namespace dedie dans les controllers (`Controllers\{Zone}\`), les composants Livewire (`Livewire\{Zone}\`) et les vues (`views/{zone}/`).

Les zones sont propres a chaque projet. Exemples courants :

| Zone | Acces typique | Exemples de pages |
|------|---------------|-------------------|
| `Web` | Public, non authentifie | Accueil, contact, inscription |
| `Admin` | Administrateur | Gestion des utilisateurs, parametres |
| `Dashboard` | Utilisateur authentifie | Tableau de bord, profil |
| `Api` | Programmatique | Endpoints REST/JSON |
| `Auth` | Authentification | Connexion, mot de passe oublie |

**Ressources multi-zone :** Quand une ressource est accessible depuis plusieurs zones (ex: un Article consultable par le public ET gerable par l'admin), son controller et ses vues se placent a la racine du namespace, sans prefixe de zone. Le controle d'acces se fait par middleware et/ou policies, pas par duplication du controller. Voir §Controllers.

### Axe 2 — Domaines metier (couches metier)

Les couches metier (Actions, Services, Repositories, Exceptions) sont organisees par domaine metier, independamment de la zone qui les appelle :

```
app/Actions/User/CreateUserAction.php         ← Appele depuis n'importe quelle zone
app/Services/User/UserCreationService.php     ← Logique metier unique
app/Repositories/User/UserReadRepository.php  ← Acces BDD centralise
```

**Un meme Action/Service/Repository peut etre utilise par n'importe quelle zone.** La segmentation par domaine garantit qu'on ne duplique jamais la logique metier entre zones.

→ Voir `rules/architecture-solid.md` pour les responsabilites et patterns de chaque couche.

---

## Arborescence generale

```
app/
├── Http/
│   └── Controllers/
│       ├── {Zone}/                      # Un dossier par zone fonctionnelle
│       │   └── {Page}Controller.php     # ex: Web/HomeController.php
│       │                                # ex: Admin/UserController.php
│       └── {Entite}Controller.php       # Ressource multi-zone (racine)
│
├── Livewire/
│   ├── {Zone}/                          # Meme segmentation que les controllers
│   │   └── {Composant}.php             # ex: Admin/UserManager.php
│   │                                    # ex: Web/ContactForm.php
│   └── Auth/                            # Composants d'authentification
│       └── LoginForm.php
│
├── Actions/{Domaine}/                   # Orchestrateurs (→ architecture-solid.md)
├── Services/{Domaine}/                  # Logique metier (→ architecture-solid.md)
├── Repositories/{Entite}/               # Acces BDD (→ architecture-solid.md)
├── Contracts/Repositories/{Entite}/     # Interfaces repositories
├── Exceptions/{Domaine}/                # Exceptions metier (→ gestion-erreurs.md)
│
├── Models/                              # Modeles Eloquent
├── Enums/                               # Enumerations PHP
├── Middleware/                           # Middleware HTTP
├── Mail/                                # Classes d'email
└── Providers/                           # Service providers
    └── RepositoryServiceProvider.php

resources/
├── views/
│   ├── layouts/
│   │   └── {zone}.blade.php            # Un layout par contexte visuel distinct
│   │                                    # ex: web.blade.php, app.blade.php
│   ├── components/                      # Composants Blade reutilisables
│   │   └── layout/
│   │       └── {composant}.blade.php    # ex: header.blade.php, footer.blade.php
│   ├── {zone}/                          # Vues par zone fonctionnelle
│   │   └── {page}/
│   │       ├── index.blade.php          # Vue principale
│   │       └── partials/                # Fragments (si page volumineuse)
│   ├── {ressource}/                     # Vues ressources multi-zone
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   ├── livewire/                        # Vues composants Livewire (auto-decouverte)
│   │   └── {zone}/
│   ├── emails/                          # Templates d'emails
│   └── errors/                          # Pages d'erreur (403, 404, 500...)
├── css/
│   ├── app.css                          # Point d'entree CSS (unique)
│   └── components/layout/               # CSS composants layout (si necessaire)
├── js/
│   ├── app.js                           # Point d'entree JS (unique)
│   └── bootstrap.js
└── (optionnel)
    ├── css/{zone}/{page}/index.css      # CSS specifique a une page (→ assets-vite.md)
    └── js/{zone}/{page}/index.js        # JS specifique a une page (→ assets-vite.md)

routes/
├── web.php                              # Routes publiques (home, langue, scheduler)
├── auth.php                             # Inscription, connexion, verification email, suspension
└── user.php                             # Zones protegees (client, loueur, admin)
```

---

## Routes

Les routes sont reparties dans plusieurs fichiers selon leur domaine fonctionnel. Chaque fichier est charge via `bootstrap/app.php` avec le middleware `web` applique automatiquement.

| Fichier | Contenu | Exemples |
|---------|---------|----------|
| `routes/web.php` | Routes publiques, scheduler | `/`, `/language/{locale}`, `/schedule/run` |
| `routes/auth.php` | Inscription, connexion, verification email, suspension | `/register/client`, `/login/lessor`, `/user/email/verify` |
| `routes/user.php` | Zones protegees (client, loueur, admin) | `/user/dashboard`, `/lessor/dashboard`, `/admin/dashboard` |

```php
// bootstrap/app.php — enregistrement des fichiers de routes
->withRouting(
    web: __DIR__.'/../routes/web.php',
    then: function (): void {
        Route::middleware('web')->group(base_path('routes/auth.php'));
        Route::middleware('web')->group(base_path('routes/user.php'));
    },
)
```

**Conventions :**

- Chaque zone a son propre groupe de routes avec les middleware qui lui sont propres
- Les zones avec un perimetre specifique utilisent un prefix URL et un prefix de nom de route (ex: `prefix('admin')->name('admin.')`)
- Les routes d'authentification (inscription, connexion, verification, suspension) vont dans `auth.php`
- Les routes protegees (espaces client, loueur, admin) vont dans `user.php`

---

## Controllers

### Segmentation par zone

Chaque zone a son propre namespace sous `app/Http/Controllers/`.

```
app/Http/Controllers/
├── Web/                         # Zone publique
│   ├── HomeController.php
│   └── ContactController.php
├── Admin/                       # Zone backoffice
│   ├── UserController.php
│   └── SettingController.php
└── PostController.php           # Ressource multi-zone (racine)
```

### Pages simples — controller invocable

Pour une page avec une seule action (affichage), utiliser un controller invocable (`__invoke`) :

```php
namespace App\Http\Controllers\Web;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('web.home.index');
    }
}
```

### Pages CRUD — resource controller

Pour les ressources avec plusieurs actions, utiliser un resource controller :

```php
namespace App\Http\Controllers\Admin;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index');
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function show(User $user): View
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }
}
```

### Ressources multi-zone

Quand une ressource est accessible depuis plusieurs zones, son controller se place a la racine (`app/Http/Controllers/`), pas dans une zone. Le controle d'acces se fait par middleware et/ou policies, pas par duplication du controller.

**Note :** Les controllers sont des points d'entree — ils deleguent la logique aux Actions/Services. Voir `rules/architecture-solid.md`.

---

## Composants Livewire

Les composants Livewire suivent la meme segmentation par zone que les controllers.

### Emplacement et correspondance classe ↔ vue

La correspondance classe ↔ vue est automatique (convention Livewire). Le namespace determine le sous-dossier de la vue.

| Classe PHP | Vue Blade |
|-----------|-----------|
| `app/Livewire/{Zone}/{Composant}.php` | `views/livewire/{zone}/{composant-kebab}.blade.php` |

Exemple : `app/Livewire/Admin/UserManager.php` → `views/livewire/admin/user-manager.blade.php`

### Convention de nommage des composants

| Pattern | Exemples | Usage |
|---------|----------|-------|
| `{Entite}Manager` | `UserManager`, `OrderManager` | Tableau interactif : recherche, filtres, tri, pagination, actions |
| `{Entite}Form` | `UserForm`, `ProfileForm` | Formulaire de creation et/ou edition |
| `{Entite}Table` | `ReportTable`, `ProductTable` | Tableau en lecture seule (affichage filtre sans CRUD) |
| `{Action}{Entite}` | `ArchiveOrder`, `ConfirmPayment` | Action ponctuelle (modal de confirmation, toggle) |

---

## Couches metier

Les Actions, Services, Repositories et Exceptions sont organises par **domaine metier**, pas par zone. Ils sont partages et reutilisables par n'importe quel controller ou composant Livewire, quelle que soit la zone.

### Arborescence

```
app/
├── Actions/{Domaine}/         # ex: Actions/User/, Actions/Order/
├── Services/{Domaine}/        # ex: Services/User/, Services/Notification/
├── Repositories/{Entite}/     # ex: Repositories/User/, Repositories/Order/
├── Contracts/Repositories/{Entite}/
└── Exceptions/{Domaine}/      # ex: Exceptions/User/, Exceptions/Order/
```

### Principe cle

La logique metier ne depend pas de la zone de presentation. Un meme Action/Service peut etre appele depuis n'importe quelle zone.

```
{ZoneA}\ComposantX   ─┐
{ZoneB}\ComposantY   ─┤──→  Action  →  Service  →  Repository
{ZoneC}\ComposantZ   ─┘
```

**Ne jamais dupliquer** une Action ou un Service pour l'adapter a une zone. Si le comportement differe entre zones, c'est la couche presentation qui adapte (parametres differents, affichage different), pas la logique metier.

→ Pour les responsabilites, patterns et regles de decision de chaque couche : voir `rules/architecture-solid.md`.
→ Pour la structure des exceptions et le flux d'erreurs : voir `rules/gestion-erreurs.md`.

---

## Layouts

Chaque contexte visuel distinct a son propre layout. Un layout est un fichier Blade qui fournit la structure HTML commune a un ensemble de pages.

**Regle :** Creer un layout quand un groupe de pages partage la meme coquille visuelle (header, sidebar, footer...). Deux zones peuvent partager un meme layout si leur structure visuelle est identique.

```
resources/views/layouts/
├── web.blade.php              # Exemple : site public (header + footer)
└── app.blade.php              # Exemple : espace authentifie (sidebar + topbar)
```

### Structure type d'un layout

```blade
{{-- resources/views/layouts/{nom}.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>@yield('title') - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('assets')
    @livewireStyles
</head>
<body>
    {{-- Structure propre a ce layout (header, sidebar, navigation...) --}}

    <main>
        @yield('content')
    </main>

    {{-- Elements globaux (toast, modals partagees...) --}}
    @livewireScripts
</body>
</html>
```

---

## Vues

### Organisation par zone

Chaque zone a son propre dossier sous `views/`. Les vues principales sont des fichiers `index.blade.php` dans un dossier nomme d'apres la page ou l'entite.

**Page simple (affichage) :**

```blade
{{-- resources/views/{zone}/{page}/index.blade.php --}}
@extends('layouts.{layout}')

@section('title', 'Titre de la page')

@section('content')
    @include('{zone}.{page}.partials.section-a')
    @include('{zone}.{page}.partials.section-b')
@endsection
```

**Page avec composant Livewire (interactivite) :**

```blade
{{-- resources/views/{zone}/{entite}/index.blade.php --}}
@extends('layouts.{layout}')

@section('title', 'Gestion des entites')

@section('content')
    <livewire:{zone}.{entite}-manager />
@endsection
```

### Pages CRUD

Les pages de gestion d'une ressource suivent un pattern CRUD standard :

```
views/{zone}/{entite-pluriel}/
├── index.blade.php       # Liste (composant Livewire Manager)
├── create.blade.php      # Creation (composant Livewire Form)
├── show.blade.php        # Detail
└── edit.blade.php        # Edition (composant Livewire Form)
```

### Partials

Les partials decoupent une page volumineuse en sections lisibles. Ils se placent dans un sous-dossier `partials/` de la page concernee.

```
views/{zone}/{page}/
├── index.blade.php
└── partials/
    ├── hero.blade.php
    ├── features.blade.php
    └── cta.blade.php
```

Les partials sont inclus via `@include('{zone}.{page}.partials.{section}')`. Ils n'ont pas de classe PHP associee.

**Regle :** Les partials servent a segmenter du contenu statique ou semi-statique. Pour du contenu interactif (formulaires, tableaux, filtres), privilegier les composants Livewire.

---

## Composants Blade reutilisables

Les composants Blade partages entre plusieurs pages se placent dans `views/components/`.

```
views/components/
├── layout/
│   ├── header.blade.php         # <x-layout.header />
│   └── footer.blade.php         # <x-layout.footer />
└── {domaine}/
    └── {composant}.blade.php    # <x-{domaine}.{composant} />
```

**Ne pas confondre :**

| Type | Emplacement | Inclusion | Etat serveur |
|------|-------------|-----------|--------------|
| Composant Blade | `views/components/` | `<x-nom />` | Non (template pur) |
| Composant Livewire | `Livewire/` + `views/livewire/` | `<livewire:nom />` | Oui (interactif) |
| Partial | `views/{zone}/{page}/partials/` | `@include()` | Non (fragment de page) |

---

## Assets CSS/JS

### Point d'entree unique (par defaut)

```
resources/css/app.css     # Point d'entree CSS (Tailwind, imports...)
resources/js/app.js       # Point d'entree JS (librairies, imports...)
```

Un seul bundle sert toutes les zones par defaut.

### Assets specifiques a une page (optionnel)

Quand une page necessite du CSS ou JS dedie :

```
resources/
├── css/{zone}/{page}/index.css
└── js/{zone}/{page}/index.js
```

```blade
@section('assets')
    @vite(['resources/css/{zone}/{page}/index.css', 'resources/js/{zone}/{page}/index.js'])
@endsection
```

→ Voir `rules/assets-vite.md` pour les details d'integration Vite.

---

## Resume des conventions

### Couche presentation (segmentee par zone)

`{Zone}` = zone fonctionnelle propre au projet (ex: `Web`, `Admin`, `Dashboard`, `Api`...).

| Element | Emplacement | Nommage |
|---------|-------------|---------|
| Controller | `Http/Controllers/{Zone}/` | `{Entite}Controller` ou `{Page}Controller` |
| Controller multi-zone | `Http/Controllers/` (racine) | `{Entite}Controller` |
| Composant Livewire | `Livewire/{Zone}/` | `{Entite}Manager`, `{Entite}Form`, `{Action}{Entite}` |
| Vue | `views/{zone}/{page}/` | `index.blade.php`, `create`, `show`, `edit` |
| Vue Livewire | `views/livewire/{zone}/` | `{composant-kebab}.blade.php` |
| Layout | `views/layouts/{nom}.blade.php` | Un layout par contexte visuel distinct |
| Partial | `views/{zone}/{page}/partials/` | `{section}.blade.php` |

### Couches metier (segmentees par domaine)

`{Domaine}` = domaine metier (ex: `User`, `Order`, `Notification`...). Les couches metier sont **partagees entre zones**.

| Element | Emplacement | Nommage | Doc de reference |
|---------|-------------|---------|------------------|
| Action | `Actions/{Domaine}/` | `{Verbe}{Entite}Action` | `architecture-solid.md` |
| Service | `Services/{Domaine}/` | `{Entite}{Responsabilite}Service` | `architecture-solid.md` |
| Repository | `Repositories/{Entite}/` | `{Entite}{Responsabilite}Repository` | `architecture-solid.md` |
| Interface | `Contracts/Repositories/{Entite}/` | `{Entite}{Responsabilite}RepositoryInterface` | `architecture-solid.md` |
| Exception | `Exceptions/{Domaine}/` | `{Entite}{Contexte}Exception` | `gestion-erreurs.md` |
| Modele | `Models/` | `{Entite}` | — |
| Enum | `Enums/` | `{Nom}` (TitleCase) | — |
| Provider | `Providers/` | `RepositoryServiceProvider` | `architecture-solid.md` |

### Schema de relation entre couches

```
Presentation (par zone)              Metier (par domaine)
┌──────────────────────┐
│  {Zone}\ComposantA   │─┐
│  {Zone}\ComposantB   │ │
├──────────────────────┤ │
│  {Zone}\ComposantC   │─┤──→  Action  →  Service  →  Repository
│  {Zone}\ComposantD   │ │    {Domaine}   {Domaine}    {Entite}
├──────────────────────┤ │
│  {Zone}\ComposantE   │─┘
└──────────────────────┘
```

La zone determine **qui** presente les donnees. Le domaine determine **comment** elles sont traitees.
