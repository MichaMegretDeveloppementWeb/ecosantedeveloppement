# Gestion des erreurs

## Principe fondamental

Toute erreur possible doit etre attrapee, loguee avec les details techniques, et remontee jusqu'a la couche de presentation sous forme de message humain. L'utilisateur ne voit jamais de donnees techniques. Le developpeur trouve tout dans les logs.

---

## Flux de propagation des erreurs

```
Repository  →  leve une exception typee (avec contexte technique)
     ↓
Service     →  attrape si besoin d'enrichir, sinon laisse remonter
     ↓
Action      →  attrape si besoin d'enrichir, sinon laisse remonter
     ↓
Controller / Livewire  →  attrape, log, affiche un message humain
```

**Regle :** Chaque couche ne `catch` que si elle a quelque chose a ajouter (contexte supplementaire, transformation de l'exception). Sinon, elle laisse l'exception remonter naturellement.

---

## Exceptions personnalisees

### Quand creer une exception personnalisee

- Des qu'une erreur est specifique a un domaine metier (pas une erreur generique PHP/Laravel)
- Des qu'une erreur doit porter un message utilisateur distinct
- Des qu'on veut distinguer deux types d'echec dans le meme flux

### Structure d'une exception personnalisee

Toutes les exceptions metier heritent d'une classe de base commune :

```php
namespace App\Exceptions;

use RuntimeException;

abstract class BaseAppException extends RuntimeException
{
    protected string $userMessage;

    public function __construct(
        string $technicalMessage,
        string $userMessage,
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        $this->userMessage = $userMessage;
        parent::__construct($technicalMessage, $code, $previous);
    }

    /** Message destine a l'utilisateur (sans donnees techniques). */
    public function getUserMessage(): string
    {
        return $this->userMessage;
    }
}
```

### Exemples d'exceptions domaine

```php
namespace App\Exceptions\User;

use App\Exceptions\BaseAppException;

class UserNotFoundException extends BaseAppException
{
    public static function byId(int $id): self
    {
        return new self(
            technicalMessage: "User not found with ID {$id}.",
            userMessage: 'Utilisateur introuvable. Veuillez reessayer.',
        );
    }
}
```

```php
namespace App\Exceptions\User;

use App\Exceptions\BaseAppException;

class UserCreationException extends BaseAppException
{
    public static function fromDatabaseError(\Throwable $e, array $data): self
    {
        return new self(
            technicalMessage: "Failed to create user. Data: " . json_encode($data) . ". Error: {$e->getMessage()}",
            userMessage: "Impossible de creer l'utilisateur. Veuillez reessayer. Si le probleme persiste, contactez le support.",
            previous: $e,
        );
    }
}
```

```php
namespace App\Exceptions\Project;

use App\Exceptions\BaseAppException;

class ProjectListException extends BaseAppException
{
    public static function loadFailed(\Throwable $e): self
    {
        return new self(
            technicalMessage: "Failed to load projects list. Error: {$e->getMessage()}",
            userMessage: 'Impossible de recuperer la liste des projets. Veuillez reessayer. Si le probleme persiste, contactez le support.',
            previous: $e,
        );
    }
}
```

### Convention de nommage

| Pattern | Exemple | Emplacement |
|---------|---------|-------------|
| `{Entite}{Contexte}Exception` | `UserNotFoundException` | `app/Exceptions/User/` |
| `{Entite}{Contexte}Exception` | `ProjectListException` | `app/Exceptions/Project/` |
| `{Domaine}{Contexte}Exception` | `PaymentFailedException` | `app/Exceptions/Payment/` |

### Factory methods statiques

Privilegier les factory methods statiques (`::byId()`, `::fromDatabaseError()`, `::loadFailed()`) plutot que `new` direct. Cela :
- Centralise la construction du message technique et du message utilisateur
- Rend le code appelant plus lisible
- Garantit la coherence des messages

---

## Pattern par couche

### Repository — lever l'exception

```php
class UserReadRepository implements UserReadRepositoryInterface
{
    public function findById(int $id): User
    {
        try {
            $user = User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw UserNotFoundException::byId($id);
        }

        return $user;
    }

    public function listActive(): Collection
    {
        try {
            return User::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        } catch (\Throwable $e) {
            throw UserListException::loadFailed($e);
        }
    }
}
```

### Service — enrichir ou laisser remonter

```php
class UserCreationService
{
    public function create(array $data): User
    {
        // Pas de try/catch ici si le repository leve deja
        // les bonnes exceptions. Laisser remonter.
        return $this->userWriteRepository->create($data);
    }
}
```

```php
// Cas ou le service doit enrichir l'erreur :
class InvoiceCalculationService
{
    public function calculateTotal(Order $order): int
    {
        try {
            $items = $this->orderItemRepository->listByOrder($order->id);
            // ... logique de calcul complexe
        } catch (OrderItemListException $e) {
            throw InvoiceCalculationException::itemsUnavailable($order->id, $e);
        }
    }
}
```

### Action — enrichir ou laisser remonter

Meme principe que le service. L'action n'attrape que si elle doit enrichir le contexte. Si le service ou le repository a deja leve la bonne exception avec le bon message utilisateur, laisser remonter.

### Controller / Composant Livewire — attraper, loguer, afficher

C'est la **derniere ligne de defense**. Ici on `catch`, on `Log`, et on affiche.

```php
// Controller
class UserController extends Controller
{
    public function index(ListUsersAction $action): View|RedirectResponse
    {
        try {
            $users = $action->execute();
        } catch (BaseAppException $e) {
            Log::error($e->getMessage(), [
                'exception' => $e,
            ]);

            return redirect()
                ->back()
                ->with('toast-error', $e->getUserMessage());
        }

        return view('admin.users.index', compact('users'));
    }

    public function store(StoreUserRequest $request, CreateUserAction $action): RedirectResponse
    {
        try {
            $user = $action->execute($request->validated());
        } catch (BaseAppException $e) {
            Log::error($e->getMessage(), [
                'exception' => $e,
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('toast-error', $e->getUserMessage());
        }

        return redirect()
            ->route('users.show', $user)
            ->with('toast-success', 'Utilisateur cree avec succes.');
    }
}
```

```php
// Composant Livewire
class UserList extends Component
{
    /** @var Collection<User>|null */
    public ?Collection $users = null;

    public function mount(ListUsersAction $action): void
    {
        try {
            $this->users = $action->execute();
        } catch (BaseAppException $e) {
            Log::error($e->getMessage(), [
                'exception' => $e,
            ]);

            $this->addError('users-load-failed', $e->getUserMessage());
        }
    }

    public function render(): View
    {
        return view('livewire.user.user-list');
    }
}
```

---

## Comportement global des erreurs HTTP

### Acces non autorise (403)

Quand un utilisateur tente d'acceder a une route ou un composant non autorise (middleware qui bloque, policy qui refuse), on ne redirige **PAS** vers une page `/unauthorized` dediee. On redirige vers la **page precedente** (`back()`) avec un toast en session :

```php
return back()->with('toast-error', __('Accès non autorisé.'));
```

Cette logique est configuree dans `bootstrap/app.php` via `$exceptions->render()` sur `AccessDeniedHttpException`.

### Session expiree / CSRF invalide (419)

Quand un token CSRF expire (session expiree, onglet reste ouvert longtemps), Laravel retourne normalement une page 419 "Page Expired". Ce comportement cause des boucles de redirection, surtout avec Livewire (les requetes XHR echouent en 419, le composant ne repond plus, recharger ne suffit pas).

**Solution :** Intercepter la reponse 419 dans `bootstrap/app.php` via `$exceptions->respond()` et rediriger vers la page precedente avec un toast :

```php
$exceptions->respond(function (Response $response, \Throwable $e, Request $request) {
    if ($response->getStatusCode() === 419) {
        return back()->with('toast-warning', __('Votre session a expiré, veuillez réessayer.'));
    }

    return $response;
});
```

**Cote Livewire** — Configurer le hook `request` pour intercepter les 419 cote client et forcer un refresh propre de la page au lieu d'afficher un dialogue bloquant :

```javascript
Livewire.hook('request', ({ fail }) => {
    fail(({ status, preventDefault }) => {
        if (status === 419) {
            preventDefault()
            window.location.reload()
        }
    })
})
```

Ce hook doit etre enregistre dans un listener `livewire:init` dans le layout principal.

### Pages suspendues

Les pages de suspension (`/user/suspended`, `/lessor/suspended`) restent des **pages dediees** — c'est le redirect target du middleware `EnsureAccountNotSuspended`. Elles affichent le motif de suspension et un lien vers le support.

### Pages d'erreur custom (404, 503)

Les pages d'erreur standard (404 Not Found, 503 Service Unavailable pour la maintenance) doivent etre **customisees** avec le design du projet. Publier les vues via `php artisan vendor:publish --tag=laravel-errors` et adapter `resources/views/errors/404.blade.php` et `resources/views/errors/503.blade.php`.

---

## Erreurs nommees — pas de variables generiques

### Principe

Chaque erreur a un **nom unique et descriptif** qui decrit ce qui a echoue. Pas de `$loadingError`, `$error`, `$errorMessage` generiques.

### Dans Livewire : `$this->addError()`

```php
// BON — erreurs nommees, specifiques
$this->addError('users-load-failed', 'Impossible de charger la liste des utilisateurs.');
$this->addError('user-creation-failed', "Impossible de creer l'utilisateur.");
$this->addError('avatar-upload-failed', "Impossible de telecharger la photo de profil.");

// MAUVAIS — generique, impossible de savoir quoi afficher ou
$this->addError('error', 'Une erreur est survenue.');
$loadingError = true;
```

### Convention de nommage des erreurs

Le nom d'erreur suit le format : `{entite}-{action}-failed`

| Nom d'erreur | Contexte |
|-------------|----------|
| `users-load-failed` | Echec du chargement de la liste des utilisateurs |
| `user-creation-failed` | Echec de la creation d'un utilisateur |
| `user-update-failed` | Echec de la mise a jour d'un utilisateur |
| `user-deletion-failed` | Echec de la suppression d'un utilisateur |
| `project-stats-load-failed` | Echec du chargement des statistiques projet |
| `avatar-upload-failed` | Echec de l'upload d'avatar |

### Affichage en vue (Livewire)

Chaque erreur nommee a un emplacement dedie dans la vue :

```blade
{{-- Zone d'erreur au-dessus du tableau --}}
@error('users-load-failed')
    <x-ui.alert type="error" dismissible>{{ $message }}</x-ui.alert>
@enderror

{{-- Le tableau ne s'affiche que si pas d'erreur de chargement --}}
@if(! $errors->has('users-load-failed'))
    <x-ui.table>
        {{-- ... --}}
    </x-ui.table>
@endif
```

```blade
{{-- Erreur sur une action specifique (dans un formulaire) --}}
@error('user-creation-failed')
    <x-ui.alert type="error" dismissible>{{ $message }}</x-ui.alert>
@enderror

{{-- Erreur sur l'upload d'avatar (a cote du champ) --}}
@error('avatar-upload-failed')
    <p class="mt-1.5 text-[12px] text-red-500">{{ $message }}</p>
@enderror
```

### Dans les controllers classiques : session flash + erreurs nommees

Pour les controllers, les erreurs non liees a la validation sont transmises via la session pour affichage en toast :

```php
// Erreur → toast via session
return redirect()->back()->with('toast-error', $e->getUserMessage());

// Succes → toast via session
return redirect()->route('users.index')->with('toast-success', 'Utilisateur cree avec succes.');
```

---

## Logging

### Principes

1. **Logger uniquement les erreurs** — pas de logs d'info "pour debug" en production. Chaque ligne de log doit avoir une raison d'exister.
2. **Logger au point de capture** — c'est le controller/composant Livewire qui log, pas les couches inferieures (sauf si la couche inferieure catch et re-throw, auquel cas elle ne log pas pour eviter les doublons).
3. **Inclure le contexte technique complet** — message, exception originale, donnees pertinentes.
4. **Jamais de donnees sensibles dans les logs** — pas de mots de passe, tokens, informations bancaires.

### Structure d'un log

```php
use Illuminate\Support\Facades\Log;

Log::error($e->getMessage(), [
    'exception' => $e,
    'user_id' => auth()->id(),
    'input' => $request->except(['password', 'password_confirmation']),
]);
```

### Niveaux de log

| Niveau | Usage |
|--------|-------|
| `Log::error()` | Erreurs qui empechent une operation de se terminer |
| `Log::warning()` | Situations anormales mais non bloquantes (ex: fallback utilise) |
| `Log::critical()` | Erreurs systeme graves (connexion BDD perdue, service externe down) |

Ne PAS utiliser `Log::info()` ou `Log::debug()` sauf besoin ponctuel de debugging (a retirer ensuite).

### Canaux de log separes par theme

Les logs doivent etre organises par **theme/domaine metier** dans des fichiers separes. Le fichier `laravel.log` par defaut ne doit pas devenir un fourre-tout : quand tout est melange dans un seul fichier, retrouver une erreur specifique devient laborieux.

**Principe :** Chaque domaine metier important a son propre fichier de log. Cela permet de :
- Retrouver rapidement les erreurs liees a un domaine precis
- Suivre l'evolution des erreurs par theme
- Partager un fichier de log specifique sans exposer les logs des autres domaines
- Configurer des politiques de retention differentes par domaine

#### Configuration dans `config/logging.php`

```php
'channels' => [
    // Canal par defaut pour les erreurs non classifiees
    'daily' => [
        'driver' => 'daily',
        'path' => storage_path('logs/laravel.log'),
        'days' => 14,
    ],

    // Canaux thematiques
    'auth' => [
        'driver' => 'daily',
        'path' => storage_path('logs/auth.log'),
        'days' => 30,
    ],
    'payments' => [
        'driver' => 'daily',
        'path' => storage_path('logs/payments.log'),
        'days' => 90,
    ],
    'external-apis' => [
        'driver' => 'daily',
        'path' => storage_path('logs/external-apis.log'),
        'days' => 30,
    ],
    'uploads' => [
        'driver' => 'daily',
        'path' => storage_path('logs/uploads.log'),
        'days' => 14,
    ],
],
```

#### Usage

```php
// Erreur d'authentification → canal 'auth'
Log::channel('auth')->error('Login failed: too many attempts', [
    'email' => $email,
    'ip' => $request->ip(),
]);

// Erreur de paiement → canal 'payments'
Log::channel('payments')->error('Payment processing failed', [
    'exception' => $e,
    'order_id' => $order->id,
]);

// Erreur d'API externe → canal 'external-apis'
Log::channel('external-apis')->warning('Geocoding API timeout', [
    'address' => $address,
]);
```

#### Quand creer un canal thematique

| Critere | Canal dedie ? |
|---------|--------------|
| Domaine critique (paiements, authentification) | **Oui** — suivi specifique obligatoire |
| Integration externe (API tierce, webhooks) | **Oui** — les erreurs externes sont frequentes et specifiques |
| Domaine avec beaucoup d'operations (uploads, imports) | **Oui** — evite de polluer le log general |
| Fonctionnalite mineure avec peu d'erreurs possibles | Non — le canal par defaut suffit |

Identifier les domaines thematiques en debut de projet et configurer les canaux correspondants. C'est un investissement minimal qui facilite enormement le debugging en production.

---

## Type d'affichage selon le type d'erreur

Deux mecanismes sont disponibles pour afficher les erreurs a l'utilisateur : `$this->addError()` (alert inline) et `$this->dispatch('toast')` (notification ephemere). **Le developpeur choisit lequel utiliser selon le contexte.** Le tableau ci-dessous donne des lignes directrices, pas des contraintes absolues :

| Type d'erreur | Affichage suggere | Mecanisme possible |
|---------------|-------------------|---------------------|
| Validation de champ | Inline sous le champ | `@error('field')` standard Laravel |
| Echec de chargement de donnees | Alert inline dans la page | `$this->addError('xxx-load-failed')` + `@error()` |
| Echec d'une action (CRUD) | Toast ou alert inline | `$this->dispatch('toast')` ou `$this->addError()` ou session `toast-error` |
| Erreur critique (page inaccessible) | Alert pleine page ou redirect + toast | Session `toast-error` |

### Guide : alert inline vs toast

Les deux approches sont des alternatives valides. Voici des indications pour guider le choix :

- **`$this->addError()` (alert inline)** — adapte quand l'erreur affecte le contenu visible de la page (ex: le tableau ne peut pas se charger → alert a la place du tableau). L'erreur reste affichee jusqu'a resolution.
- **`$this->dispatch('toast')` (notification ephemere)** — adapte quand l'erreur concerne une action ponctuelle de l'utilisateur (ex: clic sur "Supprimer" qui echoue → toast d'erreur, la page reste fonctionnelle). Le message disparait apres quelques secondes.

Le developpeur adapte selon le contexte de son composant. Dans certains cas, les deux mecanismes peuvent se combiner dans un meme composant (voir `rules/livewire.md`).

Voir `docs/toast-notifications.md` pour l'implementation du systeme de toast.

---

## Arborescence des exceptions

```
app/Exceptions/
├── BaseAppException.php          ← Classe abstraite de base
├── User/
│   ├── UserNotFoundException.php
│   ├── UserCreationException.php
│   ├── UserUpdateException.php
│   └── UserListException.php
├── Project/
│   ├── ProjectNotFoundException.php
│   └── ProjectListException.php
└── Payment/
    └── PaymentFailedException.php
```

---

## Checklist gestion d'erreur

Avant de considerer une fonctionnalite comme terminee :

- [ ] Chaque requete BDD dans un repository est dans un try/catch qui leve une exception typee
- [ ] Les exceptions portent un message technique (pour les logs) ET un message utilisateur (pour l'affichage)
- [ ] Le controller/composant Livewire catch les exceptions et log avant d'afficher
- [ ] Les erreurs sont nommees de maniere unique (`{entite}-{action}-failed`)
- [ ] Chaque erreur nommee a un emplacement dedie dans la vue
- [ ] Les logs contiennent le contexte technique sans donnees sensibles
- [ ] L'utilisateur ne voit jamais de stack trace, nom de classe, ou requete SQL
