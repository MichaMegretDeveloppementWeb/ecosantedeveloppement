# Plan d'implémentation — site Eco Santé Développement

Synthèse de la manière dont nous adaptons les règles génériques de `project-management/rules/` à **ce projet précis** : un site vitrine de 5 pages, avec **Livewire utilisé uniquement pour le formulaire de contact**, en **CSS custom (pas de Tailwind)**, sur **Laravel + Vite**.

> Documents associés :
> - `description.txt` (initialement vide — la description réelle est dans `presentation.txt`)
> - `presentation.txt` — contenu textuel et coordonnées des 3 crèches
> - `design-system.md` — référence canonique du design
> - `design-reference/` — fichiers HTML/CSS/JS source du prototype Claude Design

---

## 1. Périmètre fonctionnel

5 pages publiques, toutes côté front, **aucun espace admin, aucune authentification, aucune base de données métier**.

| # | Route | Vue | Interactivité |
|---|-------|-----|---------------|
| 1 | `/` | Accueil (hero, intro, 3 structures, valeurs, journée, témoignages, FAQ, CTA) | Statique (FAQ via `<details>` natifs) |
| 2 | `/nos-creches` | Détail des 3 micro-crèches (single page avec ancres `#amel-adam`, `#bea-benoit`, `#chiara-hugo`) | Statique |
| 3 | `/projet-pedagogique` | Projet pédagogique (promesses, 4 orientations, journée, équipe, bien-être, pourquoi) | Statique |
| 4 | `/contact` | Formulaire + téléchargement fiche de préinscription | **Livewire** (un seul composant) |
| 5 | `/mentions-legales` | Mentions légales | Statique |

Pas de pagination, pas de filtres, pas de listing dynamique. Tout est éditorial.

---

## 2. Quelles règles s'appliquent, lesquelles tombent ?

### 2.1 `architecture-solid.md` — fortement allégé

La règle générique propose Controller → Action → Service → Repository. Pour ce site :

| Couche | Conserver ? | Pourquoi |
|--------|-------------|----------|
| **Controllers** | Oui — controllers invocables (`__invoke`) | Chaque page statique = 1 controller minimaliste qui retourne sa vue |
| **Actions** | Une seule — `SendContactMessageAction` | L'envoi du mail de contact est la **seule** opération métier du site |
| **Services** | Aucun par défaut | La logique de l'Action est triviale (composer + envoyer un Mailable) ; un Service serait du sur-engineering |
| **Repositories** | Aucun | Pas de base de données métier (juste les tables Laravel par défaut) |
| **Interfaces / Contracts** | Aucun | Pas de Repository, donc pas d'interface à binder |
| **Exceptions personnalisées** | Une seule — `ContactSendException` (héritant `BaseAppException`) | Pour distinguer une erreur d'envoi de mail d'une erreur de validation |
| **`BaseAppException`** | Oui | Classe abstraite de base, garde le contrat `getUserMessage()` pour l'avenir |
| **Logging** | Oui, sur `Log::error()` dans le composant Livewire si l'envoi échoue | Pas de canal thématique dédié (un seul flux d'erreurs possible) |
| **Policies / Gates** | Aucun | Pas d'utilisateur, pas de ressource owned |
| **Transactions DB** | Aucun | Pas d'écriture multiple |

> Principe directeur de la règle SOLID : « Quand on hésite entre ajouter une couche et garder simple : garder simple. » Sur un site vitrine, la version simple est largement justifiée.

### 2.2 `livewire.md` — appliqué *uniquement* au formulaire de contact

Toutes les autres pages sont des controllers invocables qui retournent une vue Blade pure. **Pas de composant Livewire ailleurs.**

Conséquences :
- Pas de toast global, pas de système de notification générique. Le composant contact gère son propre feedback de succès/erreur en inline.
- Pas de hook `livewire:init` côté JS pour intercepter les 419 — un simple refresh suffit, ou on n'en gère pas (le formulaire de contact est tolérant).
- Le layout charge `@livewireStyles` et `@livewireScripts` car la page `/contact` les utilise. On les met dans le layout commun pour ne pas avoir à dédoubler.

### 2.3 `validation-formulaires.md` — version mono-langue simple

Le site est mono-langue (français). On n'a pas besoin de `__()` partout :

- Le composant Livewire `ContactForm` définit `rules()` et `messages()` avec **phrases françaises directes** (pas d'enveloppe `__()`).
- Pas de `validationAttributes()` — on écrit directement les phrases dans `messages()` car c'est plus lisible.
- Pas de fichier `lang/` à maintenir.

### 2.4 `gestion-erreurs.md` — version dégraissée

- `BaseAppException` à conserver (classe abstraite, 1 fichier).
- Une seule exception métier : `ContactSendException` (factory `::sendFailed(\Throwable $e)`).
- Dans `ContactForm::submit()` : `try { $action->execute($data); $this->sent = true; } catch (BaseAppException $e) { Log::error(...); $this->addError('contact-send-failed', $e->getUserMessage()); }`.
- Pas de canaux de log thématiques (un site vitrine, un seul flux d'erreurs).
- Pas de pages d'erreur 419 custom (on garde la page Laravel par défaut), mais on customise 404 et 500 avec le design system (vues `errors/404.blade.php`, `errors/500.blade.php`).

### 2.5 `conventions-nommage.md` — appliqué intégralement

Code en anglais, contenu utilisateur en français. Aucune dérogation.

Adaptations spécifiques au projet :
- Les **slugs des crèches** dans les URL et en clé interne sont en anglais kebab-case : `amel-adam`, `bea-benoit`, `chiara-hugo`. (Identifiants techniques, pas du contenu.)
- Routes : `/nos-creches`, `/projet-pedagogique`, `/mentions-legales` en français kebab-case côté URL — c'est du contenu visible, donc on garde le français comme dans le prototype. Mais les **noms de routes** Laravel sont en anglais : `creches.index`, `pedagogy.index`, `legal.index`.

### 2.6 `structure-fichiers.md` — version simplifiée

Une seule zone fonctionnelle : **Web** (public). Donc pas de segmentation `Admin/`, `Dashboard/`, etc.

### 2.7 `assets-vite.md` — segmentation par page **utile** ici

On utilise du CSS custom (pas Tailwind). La segmentation par page recommandée par la règle s'applique pleinement : un `index.css` par page + un `app.css` global qui contient tokens + composants partagés.

---

## 3. Arborescence cible du projet

```
ecosantedeveloppement/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Web/
│   │           ├── HomeController.php          # GET /
│   │           ├── CrechesController.php       # GET /nos-creches
│   │           ├── PedagogyController.php      # GET /projet-pedagogique
│   │           ├── ContactController.php       # GET /contact
│   │           └── LegalController.php         # GET /mentions-legales
│   ├── Livewire/
│   │   └── Web/
│   │       └── ContactForm.php                 # Le seul composant Livewire du site
│   ├── Actions/
│   │   └── Contact/
│   │       └── SendContactMessageAction.php    # Compose et envoie le Mailable
│   ├── Mail/
│   │   └── ContactMessageMail.php              # Mailable avec PJ optionnelle
│   ├── Exceptions/
│   │   ├── BaseAppException.php
│   │   └── Contact/
│   │       └── ContactSendException.php
│   └── Providers/
│       └── AppServiceProvider.php              # (le seul provider pour ce projet)
│
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── web.blade.php                   # Layout unique (head + header + main + footer)
│   │   ├── components/
│   │   │   └── layout/
│   │   │       ├── header.blade.php            # <x-layout.header />
│   │   │       └── footer.blade.php            # <x-layout.footer />
│   │   ├── components/
│   │   │   └── illu/                           # Composants Blade pour les illustrations SVG
│   │   │       ├── soleil.blade.php
│   │   │       ├── nuage.blade.php
│   │   │       ├── fleur.blade.php
│   │   │       ├── feuille.blade.php
│   │   │       ├── coeur.blade.php
│   │   │       ├── etoile.blade.php
│   │   │       ├── papillon.blade.php
│   │   │       ├── maison.blade.php
│   │   │       ├── enfant.blade.php
│   │   │       ├── blocs.blade.php
│   │   │       ├── livre.blade.php
│   │   │       ├── ballon.blade.php
│   │   │       └── logo.blade.php
│   │   ├── web/
│   │   │   ├── home/
│   │   │   │   ├── index.blade.php
│   │   │   │   └── partials/
│   │   │   │       ├── hero.blade.php
│   │   │   │       ├── intro.blade.php
│   │   │   │       ├── structures.blade.php
│   │   │   │       ├── valeurs.blade.php
│   │   │   │       ├── journee.blade.php
│   │   │   │       ├── temoignages.blade.php
│   │   │   │       ├── faq.blade.php
│   │   │   │       └── cta.blade.php
│   │   │   ├── creches/
│   │   │   │   ├── index.blade.php
│   │   │   │   └── partials/
│   │   │   │       ├── header.blade.php
│   │   │   │       ├── amel-adam.blade.php
│   │   │   │       ├── bea-benoit.blade.php
│   │   │   │       ├── chiara-hugo.blade.php
│   │   │   │       ├── commun.blade.php
│   │   │   │       └── cta.blade.php
│   │   │   ├── pedagogy/
│   │   │   │   ├── index.blade.php
│   │   │   │   └── partials/
│   │   │   │       ├── header.blade.php
│   │   │   │       ├── promesses.blade.php
│   │   │   │       ├── orientations.blade.php
│   │   │   │       ├── journee.blade.php
│   │   │   │       ├── equipe.blade.php
│   │   │   │       ├── bien-etre.blade.php
│   │   │   │       ├── pourquoi.blade.php
│   │   │   │       └── cta.blade.php
│   │   │   ├── contact/
│   │   │   │   ├── index.blade.php             # Contient <livewire:web.contact-form />
│   │   │   │   └── partials/
│   │   │   │       ├── header.blade.php
│   │   │   │       └── sidebar.blade.php       # Coordonnées + download fiche + mini-creches
│   │   │   └── legal/
│   │   │       └── index.blade.php
│   │   ├── livewire/
│   │   │   └── web/
│   │   │       └── contact-form.blade.php      # Vue du composant Livewire
│   │   └── errors/
│   │       ├── 404.blade.php
│   │       └── 500.blade.php
│   ├── css/
│   │   ├── app.css                             # Point d'entrée global :
│   │   │                                       #   - Google Fonts import
│   │   │                                       #   - tokens (:root)
│   │   │                                       #   - reset doux + typographie
│   │   │                                       #   - composants partagés (.btn, .card, .pill, .field, etc.)
│   │   │                                       #   - .container, .section, utilitaires
│   │   ├── components/
│   │   │   └── layout/
│   │   │       ├── header.css
│   │   │       └── footer.css
│   │   └── web/
│   │       ├── home/index.css                  # = home.css du prototype
│   │       ├── creches/index.css               # = structures.css du prototype
│   │       ├── pedagogy/index.css              # = projet.css du prototype
│   │       ├── contact/index.css               # = contact.css du prototype
│   │       └── legal/index.css                 # CSS spécifique mentions légales
│   └── js/
│       ├── app.js                              # Bootstrap minimal + Alpine standalone
│       └── web/
│           └── contact/
│               └── index.js                    # Drag & drop fichier (logique du prototype, à intégrer dans le composant Livewire ou rester en vanilla JS)
│
├── routes/
│   └── web.php                                 # Toutes les routes (5 pages) — un seul fichier suffit
│
├── public/
│   └── files/
│       └── fiche-preinscription.pdf            # Fichier PDF de préinscription téléchargeable
│
├── vite.config.js
├── composer.json
└── package.json
```

### Notes sur cette arborescence

- **Pas de `Repositories/`, `Services/`, `Contracts/`, `Models/` métier, `Enums/`** — aucune nécessité. Les Models Laravel par défaut (`User`) restent inchangés mais inutilisés en pratique.
- **Pas de `Policies/`, `Gates/`** — pas d'authentification.
- **Pas de migrations métier** — on conserve les tables Laravel par défaut juste pour ne pas casser le boot.
- **Un seul composant Livewire**, donc pas de besoin de hiérarchie complexe sous `app/Livewire/Web/`.
- **Les illustrations SVG** sont transposées du JS vers des composants Blade — meilleure intégration côté serveur, pas besoin d'attendre `DOMContentLoaded`, plus testable. La vue du prototype utilise `<span data-illu="fleur"></span>` ; nous écrirons `<x-illu.fleur class="w-12 h-12" />` (où la classe est dimensionnante).

---

## 4. Routes

Un seul fichier `routes/web.php` suffit (pas de séparation `auth.php` / `user.php` car aucune zone authentifiée).

```php
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\CrechesController;
use App\Http\Controllers\Web\PedagogyController;
use App\Http\Controllers\Web\ContactController;
use App\Http\Controllers\Web\LegalController;

Route::get('/',                    HomeController::class)->name('home');
Route::get('/nos-creches',         CrechesController::class)->name('creches.index');
Route::get('/projet-pedagogique',  PedagogyController::class)->name('pedagogy.index');
Route::get('/contact',             ContactController::class)->name('contact.index');
Route::get('/mentions-legales',    LegalController::class)->name('legal.index');
```

URL en français, noms de routes en anglais (cohérent avec `conventions-nommage.md`).

Les liens de la nav et du footer utilisent `route('...')` partout — pas de `href` en dur.

---

## 5. Plan d'attaque pour l'implémentation

Ordre d'implémentation recommandé pour rester productif :

### Phase 1 — Fondations (CSS + layout + chrome)
1. Initialiser le projet Laravel + Vite (si pas déjà fait).
2. Créer `resources/css/app.css` avec :
   - import Google Fonts
   - `:root` (tokens)
   - reset, typographie, `.eyebrow`, `.italic-accent`, `.lede`, `.muted`
   - composants partagés : `.btn-*`, `.card`, `.pill*`, `.field`, `.blob`, `.container`, `.section`, utilitaires
3. Créer le layout `layouts/web.blade.php` avec les `@vite` adaptés.
4. Implémenter les 13 illustrations SVG en composants Blade (`<x-illu.fleur />`, etc.). Le SVG vit dans le composant, pas dans un JS injecté.
5. Implémenter `<x-layout.header />` (sticky, backdrop-blur, nav, hamburger mobile via Alpine.js minimal pour le toggle) et `<x-layout.footer />`.
6. Configurer `vite.config.js` avec les points d'entrée (cf. §6 ci-dessous).

### Phase 2 — Pages statiques
7. Page d'accueil : controller `HomeController`, vue, `home/index.css`, partials. Vérifier le rendu pixel-perfect contre `design-reference/index.html`.
8. Page `/nos-creches` : controller `CrechesController`, vue, `creches/index.css`, partials par crèche. Vérifier ancres et alternance gauche/droite.
9. Page `/projet-pedagogique` : controller `PedagogyController`, vue, `pedagogy/index.css`, partials.
10. Page `/mentions-legales` : controller `LegalController`, vue, CSS dédié (peut être inline dans la page ou dans `legal/index.css`).

### Phase 3 — Formulaire de contact (Livewire)
11. Créer le Mailable `ContactMessageMail` avec template Blade et support PJ.
12. Créer l'action `SendContactMessageAction` (1 méthode `execute(array $data, ?UploadedFile $attachment): void` qui appelle `Mail::to(...)->send(new ContactMessageMail(...))`).
13. Créer le composant Livewire `Web\ContactForm` :
    - Propriétés publiques : `firstName`, `lastName`, `email`, `phone`, `creche`, `entryDate`, `message`, `rgpd`, `attachment` (avec `WithFileUploads`).
    - `rules()` et `messages()` en français direct (pas de `__()`).
    - Méthode `submit()` : validation → action → state `$sent = true` (ou `addError('contact-send-failed', ...)` si échec).
    - Pré-sélection de la crèche via `mount(?string $creche = null)` (le prototype le fait via JS et `?creche=...`).
14. Vue `livewire/web/contact-form.blade.php` avec drag & drop. Le drag & drop se fait via Alpine + Livewire (`wire:model="attachment"` + handlers Alpine pour les events `dragover` / `drop`). Garder la validation client (PDF, ≤ 5 Mo) en complément de la validation serveur Livewire.
15. Page `/contact` : sidebar (coordonnées + download de la fiche depuis `public/files/fiche-preinscription.pdf` + mini-listing des 3 crèches) + composant Livewire à droite.

### Phase 4 — Finitions
16. Vérifier la responsivité aux 6 breakpoints (980, 880, 720, 640, 560, 480).
17. Pages d'erreur 404 / 500 stylées avec le design system.
18. Tester l'envoi de mail en local (Mailpit / log driver).
19. Tester l'upload PDF (fichiers limites de taille, types autres que PDF rejetés).
20. Renseigner les vraies valeurs dans `mentions-legales` (SIRET, agrément PMI, hébergeur) — à demander au client.

---

## 6. Configuration Vite

```js
// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        // Globaux
        'resources/css/app.css',
        'resources/js/app.js',
        // Composants layout
        'resources/css/components/layout/header.css',
        'resources/css/components/layout/footer.css',
        // Pages
        'resources/css/web/home/index.css',
        'resources/css/web/creches/index.css',
        'resources/css/web/pedagogy/index.css',
        'resources/css/web/contact/index.css',
        'resources/css/web/legal/index.css',
        'resources/js/web/contact/index.js',
      ],
      refresh: true,
    }),
  ],
});
```

Chaque vue de page inclut son CSS dédié via `@push('assets') @vite([...]) @endpush` (le layout définit `@stack('assets')` dans le `<head>`).

---

## 7. Stratégie pixel-perfect

Pour garantir un rendu identique au prototype :

1. **Reprendre les CSS littéralement** depuis `design-reference/assets/`. Les coller dans la nouvelle arborescence sans modifier les valeurs (couleurs, dimensions, paddings, breakpoints).
2. **Reprendre la structure HTML littéralement** dans les vues Blade. Les classes CSS du prototype deviennent les classes Blade.
3. **Tester chaque page** en ouvrant en parallèle la version prototype (`design-reference/index.html`) et la version implémentée. Comparer visuellement.
4. **Ne pas refactorer prématurément**. Si une classe ou un sélecteur paraît bizarre, le conserver tel quel — c'est l'option la moins risquée. On pourra nettoyer plus tard si nécessaire.
5. **Les illustrations SVG** : copier-coller le contenu des `<svg>` depuis `design-reference/assets/illustrations.js` vers chaque composant Blade `x-illu.*`. Conserver les attributs `viewBox`, `aria-hidden`, et toutes les couleurs en dur (ce sont des illustrations colorées spécifiques, pas des icônes monochromes).

---

## 8. Points à valider avec le client

Avant de finaliser :

- [ ] Direction visuelle confirmée : **Pastel rose & ciel** (V1). Si V2 sauge ou V3 nuit était préférée, cela change toute la palette — à valider explicitement.
- [ ] Vraie fiche de préinscription PDF (pour l'instant, placeholder texte dans `design-reference/assets/fiche-preinscription.txt`).
- [ ] SIRET / N° d'agrément PMI / Directeur de publication / Hébergeur pour les mentions légales.
- [ ] Vraie adresse email destinataire du formulaire de contact (le prototype mentionne `ecosantedeveloppement@orange.fr`).
- [ ] Témoignages de parents : actuels = inventés. Demander de vrais témoignages (avec accord) ou les supprimer.
- [ ] Statistiques équipe (« 13 professionnelles », ratio adulte/enfant) : à confirmer.
- [ ] Photos réelles si on veut remplacer ponctuellement les illustrations SVG par des photos.

---

## 9. Récapitulatif des écarts vs règles génériques

| Règle | Écart appliqué | Justification |
|-------|----------------|---------------|
| `architecture-solid.md` § Repository | **Pas de Repository** | Pas de modèle métier persisté |
| `architecture-solid.md` § Service | **Pas de Service** | L'action est triviale |
| `architecture-solid.md` § Action | **1 seule Action** (Contact) | Une seule opération métier |
| `architecture-solid.md` § Policies | **Pas de Policy** | Pas d'utilisateur |
| `livewire.md` | Composant Livewire **uniquement** pour `ContactForm` | Un seul élément interactif sur le site |
| `validation-formulaires.md` | Messages français **directs** (pas de `__()`) | Site mono-langue |
| `gestion-erreurs.md` § canaux thématiques | **1 seul canal** (par défaut) | Surface trop petite pour justifier une segmentation |
| `gestion-erreurs.md` § page 419 custom | **Page Laravel par défaut** | Le seul formulaire est tolérant à un refresh |
| `structure-fichiers.md` § zones multiples | **1 seule zone : `Web`** | Pas d'admin |
| `assets-vite.md` § Tailwind | **CSS custom complet** | Décision projet |

Tous les autres principes — segmentation par page des assets, conventions de nommage strict, exceptions héritant de `BaseAppException`, logging au point de capture, validation exhaustive avec messages explicites — sont **maintenus tels quels**.
