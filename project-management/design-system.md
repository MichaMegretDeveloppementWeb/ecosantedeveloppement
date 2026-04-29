# Design System — Eco Santé Développement

Ce document est la **référence canonique** du design system du site. Il décrit les tokens (couleurs, typographies, espacements, ombres, rayons), les composants, et les règles d'utilisation. La cible est un **rendu pixel-perfect** par rapport au prototype Claude Design fourni dans `project-management/design-reference/`.

Direction visuelle retenue : **« Pastel rose & ciel »** (V1 du fichier `variations.html`, qui est aussi celle utilisée dans `index.html`). Les variantes V2 (sauge) et V3 (nuit) sont conservées dans `design-reference/` pour mémoire mais ne sont pas implémentées.

> Source de vérité brute : `project-management/design-reference/assets/design-system.css` + les CSS de page (`home.css`, `structures.css`, `projet.css`, `contact.css`). Ce document explique *comment lire* ces sources et *comment les transposer* dans notre arborescence Laravel/Vite (cf. `project-management/plan-implementation.md`).

---

## 1. Polices

Deux familles importées via Google Fonts (un seul `@import` dans le CSS racine) :

| Rôle | Famille | Fallback | Graisses utilisées |
|------|---------|----------|--------------------|
| Display (titres, accents italiques) | **Fraunces** | `'Georgia', serif` | 400 / 500 / 600 / 700, axe `opsz` 9..144 |
| Body (texte, UI) | **Nunito** | `system-ui, -apple-system, sans-serif` | 400 / 500 / 600 / 700 / 800 |

Variables CSS :
```css
--font-display: 'Fraunces', 'Georgia', serif;
--font-body:    'Nunito', system-ui, -apple-system, sans-serif;
```

Import (à conserver tel quel dans le point d'entrée CSS) :
```css
@import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Nunito:wght@400;500;600;700;800&display=swap');
```

---

## 2. Palette de couleurs

Toutes les couleurs sont déclarées en variables CSS sur `:root` et systématiquement utilisées via `var(--*)`. **Jamais de couleur en dur** dans les composants — toujours via une variable.

### 2.1 Échelles (chacune en 50 → 700)

```css
/* Rose poudré — chaleur, soin, accents primaires */
--rose-50:  #fdf5f3;
--rose-100: #fbe8e3;
--rose-200: #f6d0c7;
--rose-300: #efb1a3;
--rose-400: #e89180;
--rose-500: #d97058;   /* couleur d'accent principale */
--rose-600: #b85540;
--rose-700: #934234;

/* Bleu ciel — calme, confiance */
--bleu-50:  #f0f7fb;
--bleu-100: #dcecf5;
--bleu-200: #bcdaeb;
--bleu-300: #94c2dc;
--bleu-400: #6ba6ca;
--bleu-500: #4a8bb4;
--bleu-600: #3a6f93;
--bleu-700: #2f5874;

/* Jaune pâle — joie, soleil */
--jaune-50:  #fdf9ec;
--jaune-100: #faf0cc;
--jaune-200: #f5e29a;
--jaune-300: #efce5e;
--jaune-400: #e8b938;
--jaune-500: #c89a23;
--jaune-600: #9c771b;

/* Vert sauge — nature, éveil */
--sauge-50:  #f3f7f3;
--sauge-100: #e0ebe1;
--sauge-200: #c3d8c5;
--sauge-300: #9bbe9f;
--sauge-400: #74a079;
--sauge-500: #57845d;
--sauge-600: #426849;
--sauge-700: #34543b;
```

### 2.2 Crème, encres et surfaces

```css
/* Crème — fond doux global */
--creme-50:  #fefcf7;
--creme-100: #fdf8ec;
--creme-200: #f7eeda;

/* Encres (à utiliser à la place du noir pur) */
--ink-900: #2b2521;   /* brun-noir doux — texte principal, footer */
--ink-700: #4a423d;   /* texte secondaire / labels */
--ink-500: #7a6f68;   /* texte tertiaire / muted */
--ink-300: #b3a89f;
--ink-200: #d4ccc4;   /* bordures de champs */
--ink-100: #ebe5dd;   /* bordures de cards / dividers */

/* Surfaces */
--bg:        #fefcf7;  /* alias de --creme-50, fond global */
--surface:   #ffffff;  /* fond des cards, formulaires */
--surface-2: #fdf8ec;  /* alias de --creme-100, fond alterné */
```

### 2.3 Règles d'usage couleur

| Élément | Couleur |
|---------|---------|
| Fond global du site | `--bg` (crème 50) |
| Texte principal | `--ink-900` |
| Texte secondaire / paragraphes longs | `--ink-700` |
| Texte muted (légendes, métadonnées) | `--ink-500` |
| Accent primaire (eyebrows, italiques, CTA hover, dots) | `--rose-500` |
| CTA principal (background) | `--ink-900` (noir doux) → hover : `--rose-500` |
| Bordure de carte / divider | `--ink-100` |
| Bordure de champ formulaire | `--ink-200` |
| Footer | fond `--ink-900`, texte `--creme-100` / `--creme-200` |

**Pas de noir pur (`#000`).** Toujours `--ink-900`.

---

## 3. Typographie

### 3.1 Tailles

Toutes les tailles `h1`/`h2`/`h3` sont **fluides** via `clamp()` — ne jamais les figer. `h4` et corps sont fixes.

```css
h1 { font-family: var(--font-display); font-weight: 500; font-size: clamp(40px, 5.5vw, 68px); line-height: 1.05; letter-spacing: -0.01em; }
h2 { font-family: var(--font-display); font-weight: 500; font-size: clamp(28px, 3.5vw, 42px); line-height: 1.15; letter-spacing: -0.01em; }
h3 { font-family: var(--font-display); font-weight: 500; font-size: clamp(20px, 2vw, 26px); line-height: 1.25; }
h4 { font-family: var(--font-body);    font-weight: 600; font-size: 18px; }
body { font-family: var(--font-body); font-size: 16px; line-height: 1.6; color: var(--ink-900); background: var(--bg); }
```

### 3.2 Styles spéciaux

| Classe | Rôle | Spec |
|--------|------|------|
| `.eyebrow` | Petite accroche au-dessus des titres | Nunito 700, 12px, `letter-spacing: 0.18em`, uppercase, couleur `--rose-500` |
| `.italic-accent` | Mot en italique dans un titre | Fraunces italique 400, couleur `--rose-500`. Utilisé inline via `<em class="italic-accent">…</em>` |
| `.lede` | Paragraphe d'introduction | `clamp(17px, 1.4vw, 19px)` line-height 1.6, couleur `--ink-700`, `max-width: 60ch` |
| `.muted` | Texte tertiaire | couleur `--ink-500` |

### 3.3 Pattern de titre signature

Tous les `h1`/`h2` du site suivent le même pattern de double ligne avec accent italique :

```html
<h2>Trois maisons,<br><em class="italic-accent">une famille</em>.</h2>
```

Toujours un `<br>` (pas un retour à la ligne CSS) et un point final.

### 3.4 Anti-aliasing

```css
body {
  -webkit-font-smoothing: antialiased;
  text-rendering: optimizeLegibility;
}
```

---

## 4. Espacements

Échelle d'espacement basée sur des variables :

```css
--space-1: 4px;
--space-2: 8px;
--space-3: 12px;
--space-4: 16px;
--space-5: 24px;
--space-6: 32px;
--space-7: 48px;
--space-8: 64px;
--space-9: 96px;
```

Sections de page : padding vertical `var(--space-9)` (96px) par défaut, `var(--space-7)` (48px) pour les sections compactes (`.section-sm`).

```css
.section    { padding: var(--space-9) 0; }
.section-sm { padding: var(--space-7) 0; }
```

### Container

```css
.container {
  width: 100%;
  max-width: 1240px;
  margin: 0 auto;
  padding: 0 32px;
}
@media (max-width: 720px) {
  .container { padding: 0 20px; }
}
```

---

## 5. Rayons et formes

```css
--radius-sm:   8px;
--radius:     14px;       /* champs, petits boutons internes, journee-card */
--radius-lg:  22px;       /* cards, sections, contact-cards */
--radius-xl:  32px;       /* hero-card, structure-visual, cta-card, contact-form */
--radius-blob: 60% 40% 55% 45% / 50% 60% 40% 50%;  /* forme organique pour les blobs décoratifs */
```

Boutons et pills : **`border-radius: 999px`** (pleine ronde).

---

## 6. Ombres

Quatre niveaux, tous très doux et basés sur l'encre `--ink-900` à faible opacité :

```css
--shadow-xs: 0 1px 2px  rgba(43, 37, 33, 0.04);
--shadow-sm: 0 2px 8px  rgba(43, 37, 33, 0.05);
--shadow:    0 8px 24px rgba(43, 37, 33, 0.07);
--shadow-lg: 0 20px 50px rgba(43, 37, 33, 0.10);
```

Règle : pas d'ombre par défaut sur les cards. L'ombre n'apparaît qu'au **hover** (élévation visuelle) ou sur les éléments « flottants » (badges, hero-badge).

---

## 7. Composants

### 7.1 Boutons

Tous les boutons partagent la base `.btn` (capsule, bold, gap pour icône).

```css
.btn {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  padding: 14px 26px;
  border-radius: 999px;
  font-weight: 700;
  font-size: 15px;
  transition: all 0.2s ease;
  white-space: nowrap;
}
.btn-primary   { background: var(--ink-900); color: var(--creme-50); }
.btn-primary:hover   { background: var(--rose-500); transform: translateY(-1px); }

.btn-secondary { background: var(--surface); color: var(--ink-900); border: 1.5px solid var(--ink-200); }
.btn-secondary:hover { border-color: var(--ink-900); background: var(--creme-100); }

.btn-ghost     { color: var(--ink-700); padding: 10px 18px; }
.btn-ghost:hover { color: var(--rose-500); }
```

Icône à droite : flèche `→` SVG 16px, `stroke-width: 2.5`, `stroke-linecap: round`. Voir `index.html` pour le snippet exact.

### 7.2 Cards

```css
.card {
  background: var(--surface);
  border-radius: var(--radius-lg);
  padding: 28px;
  border: 1px solid var(--ink-100);
  transition: all 0.25s ease;
}
.card:hover {
  border-color: var(--ink-200);
  box-shadow: var(--shadow);
  transform: translateY(-2px);
}
```

### 7.3 Pills (tags colorés)

Capsules petites (13px). Quatre variantes selon la palette de chaque structure / valeur.

```css
.pill        { background: var(--rose-50);  color: var(--rose-600); padding: 6px 14px; border-radius: 999px; font-size: 13px; font-weight: 600; }
.pill-bleu   { background: var(--bleu-50);  color: var(--bleu-600); }
.pill-jaune  { background: var(--jaune-50); color: var(--jaune-600); }
.pill-sauge  { background: var(--sauge-50); color: var(--sauge-600); }
```

Usage : « Val d'Oise · 95 » sur les cards de structure, et derrière chaque crèche : Amel & Adam → rose, Béa & Benoit → jaune, Chiara & Hugo → bleu.

### 7.4 Header (sticky avec backdrop-blur)

```css
.site-header {
  position: sticky; top: 0; z-index: 50;
  background: rgba(254, 252, 247, 0.85);   /* crème translucide */
  backdrop-filter: blur(10px);
  border-bottom: 1px solid var(--ink-100);
}
.nav { display: flex; align-items: center; justify-content: space-between; padding: 18px 0; }
.nav-logo {
  display: flex; align-items: center; gap: 12px;
  font-family: var(--font-display); font-size: 22px; font-weight: 600;
  color: var(--ink-900);
}
.nav-links { display: flex; gap: 28px; align-items: center; }
.nav-links a { font-size: 15px; font-weight: 600; color: var(--ink-700); }
.nav-links a:hover { color: var(--rose-500); }
.nav-links a.active { color: var(--ink-900); }
.nav-links a.active::after {
  content: ''; position: absolute; left: 0; right: 0; bottom: -6px;
  height: 3px; background: var(--rose-300); border-radius: 999px;
}
```

Mobile (< 880px) : bouton hamburger 44×44 rond `--creme-100`. Menu déplié en colonne sous le header. Voir `assets/chrome.js` pour le toggle.

Liens (ordre exact) : **Accueil · Nos crèches · Projet pédagogique · Contact** + bouton CTA *Inscription* (`.btn-primary`, padding `12px 22px`).

### 7.5 Footer (dark)

Fond `--ink-900`, texte `--creme-100`. Grille 4 colonnes (1.4fr / 1fr / 1fr / 1fr) :

1. Logo + tagline
2. Nos crèches (3 liens)
3. Le projet (4 liens)
4. Contact (téléphone, email, lien contact, mentions légales)

Ligne du bas : `display: flex; justify-content: space-between` avec copyright + agrément PMI. Mobile : grille en 2 colonnes, ligne du bas en `flex-direction: column`.

### 7.6 Champs de formulaire

```css
.field { display: flex; flex-direction: column; gap: 8px; }
.field label { font-size: 13px; font-weight: 700; color: var(--ink-700); letter-spacing: 0.02em; }
.field input,
.field textarea,
.field select {
  font: inherit; font-size: 15px;
  padding: 14px 18px;
  border-radius: var(--radius);
  border: 1.5px solid var(--ink-200);
  background: var(--surface);
  color: var(--ink-900);
  transition: all 0.2s;
}
.field input:focus,
.field textarea:focus,
.field select:focus {
  outline: none;
  border-color: var(--rose-400);
  box-shadow: 0 0 0 4px var(--rose-50);   /* halo rose pâle */
}
.field textarea { min-height: 120px; resize: vertical; font-family: inherit; }
```

`<span class="required">*</span>` après chaque label de champ obligatoire (couleur `--rose-500`, font-weight 700).

### 7.7 Radio « cards » (sélection de crèche)

Pattern utilisé dans la page contact :

```html
<label class="radio-card">
  <input type="radio" name="creche" value="amel-adam">
  <span class="radio-content">
    <span class="dot dot-rose"></span>
    <strong>Amel &amp; Adam</strong>
    <small>Deuil-la-Barre · 95</small>
  </span>
</label>
```

L'input est masqué (`opacity: 0; position: absolute;`). Le visuel est porté par `.radio-content`. Sélectionné → bordure `--rose-400`, fond `--rose-50`, halo `box-shadow: 0 0 0 4px var(--rose-50)`. Voir `assets/contact.css` pour le détail.

### 7.8 Upload PDF (drag & drop)

Zone en pointillés avec icône, message, lien « parcourez vos fichiers ». Au drag : bordure `--rose-400`, fond `--rose-50`. Une fois un fichier déposé : carte `.upload-filled` (icône + nom + taille + bouton suppression).

Validation client : type `application/pdf` uniquement, taille max 5 Mo. Voir `contact.html` lignes 240-275 pour la logique JS de référence.

### 7.9 Blobs décoratifs

Formes organiques en arrière-plan (positionnées en `position: absolute`, `z-index: 0`). Utilisent `--radius-blob` et les couleurs 100/200 de la palette à faible opacité (0.5–0.7). Toujours `pointer-events: none`. Le contenu de section est mis en `position: relative; z-index: 1` pour passer devant.

### 7.10 Illustrations SVG

Bibliothèque inline dans `assets/illustrations.js` (objet `IconLibrary`). 13 illustrations + 1 logo :

| Clé | Usage |
|-----|-------|
| `soleil`, `nuage`, `fleur`, `feuille`, `coeur`, `etoile`, `papillon` | Décoratif partout |
| `maison` | Logo de la maison, cards valeurs |
| `enfant` | Pages projet / équipe |
| `blocs` | Cubes alphabet (équipe) |
| `livre` | Téléchargement, fiche pédagogique |
| `ballon` | Illustrations CTA |
| `vague` | Séparateur de section optionnel |
| `logo` | Logo de la marque (header / footer) |

Mécanisme : un élément avec `data-illu="<nom>"` voit son innerHTML remplacé au chargement par le SVG correspondant. **Ce mécanisme JS n'est pas optimal pour Laravel/Blade** : on transposera en composants Blade `<x-illu.fleur />` (cf. `plan-implementation.md` §5).

---

## 8. Patterns de page

### 8.1 Hero (homepage)

Grille 1.05fr / 1fr (texte / visuel) avec gap 64px. Texte avec eyebrow → h1 → lede → boutons → bloc stats. Visuel : carré (aspect-ratio 1/1) avec illustrations positionnées en absolute, badge « Agréées PMI » en bas-gauche débordant. 3 blobs décoratifs en background.

Mobile (< 980px) : grille en colonne unique, gap 48px, padding réduit.

### 8.2 Page header (autres pages)

Section `.page-header` avec eyebrow + h1 + lede. Padding `80px 0 56px`. 2 blobs décoratifs. Le `.container` interne est limité à `max-width: 880px` (lecture confortable). Variante de couleur des blobs selon la page (rose/bleu/jaune sur contact, rose/jaune/bleu sur structures, rose/bleu/sauge sur projet).

### 8.3 Section avec head

```html
<div class="section-head">
  <span class="eyebrow">Eyebrow</span>
  <h2>Titre <em class="italic-accent">accent</em>.</h2>
  <p class="lede">Sous-titre optionnel.</p>
</div>
```

`margin-bottom: 56px`, `max-width: 720px`. Variante centrée : ajouter `text-center`.

### 8.4 Cards de structure (homepage)

3 cards en grille `repeat(3, 1fr)`, gap 28px. Chacune : `<a>` plein clic, illustrée par un grand SVG centré dans une zone colorée 180px de haut (`--rose-50` / `--jaune-50` / `--bleu-50`), suivie d'un body avec pill, h3, adresse, métadonnées (10 enfants · ouverte 5j/7) et lien « En savoir plus → ».

Hover : `translateY(-4px)`, `box-shadow: var(--shadow-lg)`, bordure `--ink-200`.

### 8.5 Timeline « journée type » (homepage)

Liste verticale avec rail à gauche. Chaque step :
- Heure absolue à gauche (`--rose-500`, Fraunces 22px)
- Dot 14×14 sur le rail (`--rose-300`, halo blanc + halo rose)
- Card 18×24 avec strong + description

Le rail est un `<div class="journee-line">` `position: absolute; left: 88px; top: 14px; bottom: 14px; width: 2px; background: var(--ink-100)`.

### 8.6 Témoignages

3 figures `<figure class="temoignage">` en grille. Background `--rose-50` pour la section. Chaque figure : grosse guillemet « " » (Fraunces 80px, `--rose-200`) en haut-droite absolute, blockquote en italique Fraunces 18px, figcaption avec avatar coloré (rose/bleu/jaune/sauge en `--xxx-100` / texte `--xxx-700`) et nom + sous-titre muted.

### 8.7 FAQ (`<details>`)

Grille 1fr / 1.3fr (intro / liste). Chaque `<details class="faq-item">` :
- Bordure `--ink-100`, padding `4px 24px`, radius `--radius`
- Quand ouvert : bordure `--rose-200`, fond `--rose-50`
- Summary : Fraunces 18px 500, marker custom `+` / `−` dans rond rose

```css
.faq-item summary::-webkit-details-marker { display: none; }
.faq-item summary::after {
  content: '+';
  width: 28px; height: 28px;
  border-radius: 50%;
  background: var(--rose-50);
  color: var(--rose-500);
  font-size: 24px;
}
.faq-item[open] summary::after { content: '−'; background: var(--rose-200); }
```

### 8.8 CTA dark (fin de page)

Grand bloc à fond gradient noir `linear-gradient(135deg, var(--ink-900), #3a322d)`, `border-radius: var(--radius-xl)`, padding `80px 64px` (mobile : `56px 32px`). Eyebrow en `--jaune-300`, h2 en `--creme-50`, lede en `--ink-200`. Boutons : primary en `--rose-400` sur fond `--ink-900`, secondaire en outline blanc transparent.

Optionnel : 2-3 illustrations en absolute (ballon, enfant, étoile) en flottement à droite — masquées en mobile.

### 8.9 Page « structures »

Pour chaque crèche, section `.structure-detail` avec grille texte/visuel (alternée gauche/droite via `.structure-detail-grid-rev` sur la 2e). Visuel : carré coloré (rose/jaune/bleu en `--xxx-50`) avec un blob plus saturé au centre (`--xxx-100`), illustration principale 50% au centre, 3 illustrations satellites plus petites, et badge « 10 berceaux » en bas-droite.

Tronc commun (4 items) en bas, section `--rose-50`.

### 8.10 Page « projet pédagogique »

Promesses (2 cards larges, 48×40 padding, fond `--rose-50` / `--bleu-50`), 4 orientations (cards horizontales avec n° XL en `--rose-300`, contenu, illu droite, hover `translateX(4px)`), journée en 6 cards colorées rotatives, bloc équipe (visuel + chiffres-clés), bien-être (3 cards), pourquoi nous choisir (5 items numérotés), CTA dark.

### 8.11 Page contact

Grille 360px / 1fr (sidebar / formulaire). Sidebar sticky (`top: 96px`) sur desktop avec 3 cards : coordonnées, téléchargement fiche PDF, mini-liste des 3 crèches. Formulaire : grand bloc `--surface` 48px de padding, h2 puis champs en grille 2 colonnes (prénom/nom, email/téléphone), radios crèche, date d'entrée (`type="month"`), message, upload PDF, checkbox RGPD, bouton submit large.

Mobile (< 980px) : passage en colonne unique, sidebar non sticky.

### 8.12 Page mentions légales

Layout simple, container `max-width: 760px`, padding 80px. Eyebrow + h1 + lede + sommaire dans un bloc `--rose-50`, puis sections `<h2>` séparées par un border-top `--ink-100`. À compléter par le client : SIRET, agrément PMI, hébergeur.

---

## 9. Responsive

Breakpoints utilisés (en `max-width`, mobile-first par défaut) :

| Breakpoint | Effet typique |
|------------|---------------|
| `980px` | Hero passe en colonne unique, contact-grid en 1 col |
| `880px` | Cards en colonne unique, footer passe en 2 colonnes, équipe en colonne, FAQ en colonne |
| `720px` | Container padding réduit à 20px, footer ligne du bas en colonne |
| `640px` | Timeline journée resserrée |
| `560px` | Form-grid en colonne unique, radio-group en colonne, valeurs en colonne, moments en colonne |
| `480px` | Tronc commun en colonne |

Aucun usage de `min-width` — toutes les media queries sont `max-width`.

---

## 10. Accessibilité — points à respecter

- Tous les SVG décoratifs ont `aria-hidden="true"`
- Les liens « plein clic » de structure card sont des `<a>` qui englobent le contenu
- Les radio-cards utilisent un vrai `<input type="radio">` masqué visuellement (pas un faux switch)
- Les `<details>` natives pour la FAQ (clavier, lecteurs d'écran ok)
- Bouton hamburger avec `aria-label="Menu"`
- Bouton supprimer fichier upload avec `aria-label="Supprimer"`
- Focus visible : sur les champs, halo rose + bordure rose. À conserver pour la navigation au clavier
- Texte sur fond `--ink-900` : utiliser `--creme-50`/`--creme-100`/`--creme-200`, jamais des gris

---

## 11. Récap des tokens à reprendre tels quels

Le fichier `project-management/design-reference/assets/design-system.css` est la **source de vérité littérale** des tokens. Lors de l'implémentation :

1. **Reprendre intégralement** le `:root` (variables couleurs, polices, espacements, rayons, ombres).
2. **Reprendre intégralement** le reset doux, les styles `h1-h4`, `.eyebrow`, `.italic-accent`, `.lede`, `.muted`.
3. **Reprendre intégralement** les composants partagés : `.container`, `.section`, `.btn-*`, `.card`, `.pill*`, `.site-header`, `.site-footer`, `.field`, `.blob`, utilitaires (`.flex`, `.grid`, `.gap-*`, `.mb-*`…).
4. **Réorganiser par page** les CSS spécifiques (`home.css`, `structures.css`, `projet.css`, `contact.css`) selon notre arborescence (cf. `plan-implementation.md` §3).

L'objectif est un rendu strictement identique. Toute optimisation (purge, minification) se fait via Vite à la compilation, sans modifier les valeurs.
