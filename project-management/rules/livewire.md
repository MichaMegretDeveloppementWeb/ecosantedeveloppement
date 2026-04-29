# Approche Livewire

## Contexte

Ce document s'applique lorsque le projet adopte **Livewire comme mode d'interaction principal** pour ses pages dynamiques. Si le projet n'utilise pas Livewire, ce document peut etre ignore.

Il explique comment les responsabilites se repartissent entre controllers et composants Livewire, et comment transposer les principes d'architecture, de validation et de gestion d'erreurs a ce contexte.

---

## Ce que Livewire change (et ce qu'il ne change pas)

### Ce qui ne change PAS

Le flux HTTP reste identique :

```
Route  →  Controller  →  Vue Blade
```

- Les routes pointent **toujours** vers un controller
- Le controller rend **toujours** une vue Blade
- Meme si la vue ne contient qu'un seul composant Livewire, le controller et la vue existent

### Ce qui change

Dans un flux classique, le **controller** appelle les Actions/Services, recupere les donnees, et les passe a la vue. La vue est passive — elle affiche ce qu'on lui donne.

Avec Livewire, le **controller** se contente de rendre la vue (il devient "thin"). C'est le **composant Livewire** embarque dans la vue qui prend la responsabilite d'appeler les couches metier : recuperation des donnees, filtrage, tri, pagination, soumission de formulaire, etc.

```
Flux classique :
  Route → Controller → Action → donnees → Vue (passive)

Flux Livewire :
  Route → Controller → Vue contenant <livewire:composant />
                              ↓
                    Composant Livewire → Action → donnees → rendu reactif
```

### Exemple concret

**Sans Livewire** — le controller fait le travail :

```php
class UserController extends Controller
{
    public function index(ListUsersAction $action): View
    {
        $users = $action->execute(['status' => 'active']);

        return view('admin.users.index', compact('users'));
    }
}
```

**Avec Livewire** — le controller est thin, le composant fait le travail :

```php
// Controller : rend simplement la vue
class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index');
    }
}
```

```blade
{{-- Vue : contient le composant Livewire --}}
@extends('layouts.app')

@section('content')
    <livewire:admin.user-manager />
@endsection
```

```php
// Composant Livewire : gere la logique interactive
class UserManager extends Component
{
    public string $search = '';
    public string $sortBy = 'name';

    public function render(): View
    {
        // C'est le composant qui appelle l'Action, pas le controller
        $users = app(ListUsersAction::class)->execute([
            'search' => $this->search,
            'sort' => $this->sortBy,
        ]);

        return view('livewire.admin.user-manager', compact('users'));
    }
}
```

---

## Repartition des responsabilites

| Responsabilite | Sans Livewire | Avec Livewire |
|----------------|---------------|---------------|
| Point d'entree HTTP (route) | Controller | Controller (inchange) |
| Rendre la vue | Controller | Controller (inchange) |
| Appeler les Actions/Services | Controller | **Composant Livewire** |
| Gerer les filtres, tri, pagination | Controller (via query params) | **Composant Livewire** (reactif) |
| Soumettre un formulaire | Controller via FormRequest | **Composant Livewire** via `rules()`/`messages()` |
| Afficher le feedback (succes/erreur) | `redirect()->with()` (session flash) | **Composant** via `dispatch('toast')` / `addError()` |
| Validation des champs | FormRequest | **Composant** via `$this->validate()` |

### Le controller en mode Livewire

Le controller devient un simple passeur de vue. Il ne contient plus de logique metier :

```php
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

**Note :** Le controller peut passer des donnees simples a la vue (ex: un modele resolu par route model binding). Mais la logique interactive (filtrage, soumission, actions) est dans le composant Livewire.

---

## Architecture en couches avec Livewire

L'architecture Action → Service → Repository (→ `rules/architecture-solid.md`) ne change pas. Seul l'appelant change : c'est le composant Livewire au lieu du controller.

### Pattern formulaire (creation/edition)

```php
use App\Actions\User\CreateUserAction;
use App\Exceptions\BaseAppException;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class UserForm extends Component
{
    public string $name = '';
    public string $email = '';
    public string $role = 'member';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:admin,member,viewer'],
        ];
    }

    protected function messages(): array
    {
        // Messages dans la langue de l'application
        // (→ rules/validation-formulaires.md)
        return [
            'name.required' => 'Le nom est obligatoire.',
            'name.string' => 'Le nom doit etre une chaine de caracteres.',
            'name.min' => 'Le nom doit contenir au moins 2 caracteres.',
            'name.max' => 'Le nom ne peut pas depasser 100 caracteres.',
            'email.required' => "L'adresse email est obligatoire.",
            'email.email' => "L'adresse email n'est pas valide.",
            'email.max' => "L'adresse email ne peut pas depasser 255 caracteres.",
            'email.unique' => 'Cette adresse email est deja utilisee.',
            'role.required' => 'Le role est obligatoire.',
            'role.in' => 'Le role selectionne est invalide.',
        ];
    }

    public function save(CreateUserAction $action): void
    {
        $validated = $this->validate();

        try {
            $action->execute($validated);

            // Signature simplifiee — la signature reelle depend du composant toast du projet
            $this->dispatch('toast', type: 'success', message: 'Utilisateur cree avec succes.');
            $this->redirect(route('users.index'));
        } catch (BaseAppException $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            $this->dispatch('toast', type: 'error', message: $e->getUserMessage());
        }
    }
}
```

> **Note :** Si l'application est multi-langue, les messages de validation doivent passer par `__()` et le systeme `validationAttributes()`. Voir `rules/validation-formulaires.md`.

### Pattern chargement de donnees

```php
use App\Actions\User\ListUsersAction;
use App\Exceptions\BaseAppException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class UserList extends Component
{
    public ?Collection $users = null;

    public function mount(ListUsersAction $action): void
    {
        $this->loadUsers($action);
    }

    public function loadUsers(?ListUsersAction $action = null): void
    {
        $action ??= app(ListUsersAction::class);

        try {
            $this->users = $action->execute();
            $this->resetErrorBag('users-load-failed');
        } catch (BaseAppException $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            $this->addError('users-load-failed', $e->getUserMessage());
        }
    }
}
```

Vue correspondante (les composants UI ci-dessous sont **a titre d'illustration** — adapter au design system du projet) :

```blade
<div>
    @error('users-load-failed')
        {{-- Alert inline a la place du contenu absent --}}
        <div class="alert-erreur">
            {{ $message }}
            <button wire:click="loadUsers">Reessayer</button>
        </div>
    @else
        {{-- Affichage normal (tableau, liste, grille...) --}}
    @enderror
</div>
```

---

## Validation Livewire — les regles

1. **`rules()` et `messages()`** directement dans le composant. Pas de FormRequest.
2. **`$this->validate()`** dans la methode d'action (`save`, `update`, etc.).
3. **`$this->validateOnly('champ')`** uniquement pour le feedback en temps reel quand c'est pertinent (unicite email, format complexe).
4. **`wire:model.blur`** pour la liaison des champs (valide au blur). `wire:model.live.debounce.300ms` uniquement pour la recherche.
5. **Affichage inline** sous chaque champ avec `@error('champ')`.

→ Pour les regles completes de validation : voir `rules/validation-formulaires.md`.

---

## Gestion d'erreur Livewire — les deux mecanismes

Livewire offre deux mecanismes pour remonter les erreurs. **Le developpeur choisit lequel utiliser selon le contexte.** Il n'y a pas de regle absolue — les deux sont des alternatives valides. Voici des lignes directrices pour guider le choix :

### `$this->addError()` — erreur contextuelle, liee a un endroit precis de la page

Adapte quand l'erreur **affecte le contenu visible** et doit rester affichee jusqu'a resolution.

```php
$this->addError('users-load-failed', $e->getUserMessage());
$this->addError('stats-load-failed', $e->getUserMessage());
$this->addError('avatar-upload-failed', $e->getUserMessage());
```

La vue affiche l'erreur a l'endroit precis ou le contenu aurait du apparaitre.

**Quand l'utiliser :**
- Le tableau ne peut pas se charger → alert a la place du tableau
- Un bloc de statistiques echoue → alert a la place des stats
- L'upload d'un fichier echoue → message sous la zone d'upload

### `$this->dispatch('toast', ...)` — feedback ponctuel, reaction a une action utilisateur

Adapte quand l'utilisateur a **fait une action** et qu'il faut un retour temporaire.

```php
// Signature simplifiee — la signature reelle depend du composant toast du projet
$this->dispatch('toast', type: 'error', message: $e->getUserMessage());
$this->dispatch('toast', type: 'success', message: 'Utilisateur cree avec succes.');
```

**Quand l'utiliser :**
- L'utilisateur clique "Enregistrer" et ca echoue → toast erreur
- L'utilisateur clique "Supprimer" et ca reussit → toast succes
- L'utilisateur clique "Copier le lien" → toast info

**Note :** Le mecanisme d'affichage des toasts (composant, position, duree) depend du design system du projet. Le pattern `$this->dispatch('toast', ...)` est une convention Livewire/Alpine — le composant qui l'ecoute est a implementer selon le projet.

### Guide de decision

| L'erreur concerne... | Mecanisme suggere |
|----------------------|-------------------|
| Du contenu qui devrait etre affiche sur la page | `addError()` + alert inline |
| Une action ponctuelle de l'utilisateur (clic sur un bouton) | `dispatch('toast')` |
| La validation d'un champ de formulaire | `$this->validate()` + `@error('champ')` inline |

Ce tableau est un guide, pas une contrainte absolue. Le developpeur adapte selon le contexte de son composant.

### Combiner les deux

Dans certains cas, les deux mecanismes se combinent. Par exemple, un composant qui charge des donnees ET permet des actions :

```php
class ProjectDashboard extends Component
{
    public ?Collection $projects = null;

    public function mount(): void
    {
        // Chargement → addError si echec
        try {
            $this->projects = app(ListProjectsAction::class)->execute();
        } catch (BaseAppException $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $this->addError('projects-load-failed', $e->getUserMessage());
        }
    }

    public function archive(int $projectId): void
    {
        // Action utilisateur → toast si echec
        try {
            app(ArchiveProjectAction::class)->execute($projectId);
            $this->dispatch('toast', type: 'success', message: 'Projet archive avec succes.');
            $this->loadProjects();
        } catch (BaseAppException $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            $this->dispatch('toast', type: 'error', message: $e->getUserMessage());
        }
    }
}
```

→ Pour le detail des patterns de gestion d'erreur : voir `rules/gestion-erreurs.md`.

---

## Quand utiliser un controller classique (avec logique)

Meme dans un projet Livewire, les controllers avec logique metier restent pertinents pour :

| Situation | Pourquoi |
|-----------|----------|
| Pages statiques (accueil, a-propos) | Pas d'interactivite, un controller invocable suffit |
| Redirections (callback OAuth, retour paiement) | Flux redirect pur, pas de composant |
| Webhooks | Reception de donnees externes, pas d'interface |
| Telechargement de fichiers | Response stream, pas de composant |
| API endpoints | JSON, pas de vue |
| Pages sans interactivite dynamique | Le controller peut appeler une Action et passer les donnees a la vue |

Dans ces cas, appliquer les patterns de `rules/architecture-solid.md` et `rules/gestion-erreurs.md` version controller (avec FormRequest, `redirect()->with()`, etc.).

---

## Resume

Avec Livewire, le **controller reste le point d'entree HTTP** mais devient thin (il rend la vue sans logique metier). Le **composant Livewire reprend la responsabilite** d'appeler les couches metier :

| Concept | Sans Livewire | Avec Livewire |
|---------|---------------|---------------|
| Point d'entree HTTP | Controller | Controller (inchange) |
| Appel aux Actions/Services | Controller | Composant Livewire |
| Validation | FormRequest | `rules()` / `messages()` dans le composant |
| Feedback succes/erreur | `redirect()->with('toast-...')` | `$this->dispatch('toast', ...)` |
| Erreur contextuelle | `redirect()->withErrors(...)` | `$this->addError('nom-specifique', '...')` |
| Donnees vers la vue | `return view(..., compact(...))` | Proprietes publiques + `render()` |

Les principes (couches SOLID, erreurs nommees, logging, messages utilisateur) sont identiques. Seul l'appelant des couches metier change.
