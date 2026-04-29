# Architecture SOLID — Controller/Livewire → Action → Service → Repository

## Principe fondamental

Toute logique metier et toute interaction avec la base de donnees suivent une architecture en couches. Cette architecture s'applique a **l'ensemble de l'application** (pages publiques, admin, API) des lors qu'il y a de la logique ou de la manipulation de donnees.

L'objectif est la **segmentation** : chaque couche a une responsabilite unique, chaque fichier reste court et focalisé. Segmentation = maintenabilite = evolutivite.

---

## Les 4 couches

```
Controller / Composant Livewire
        ↓
      Action          ← Orchestrateur (single-responsibility)
      ↓    ↓
   Service  Service   ← Logique metier
      ↓       ↓
  Repository  Repository  ← Interaction BDD
```

### 1. Controller / Composant Livewire

**Role :** Point d'entree HTTP ou composant interactif. Recoit la requete, delegue a une Action, retourne la reponse.

**Ne doit PAS contenir :**
- De logique metier
- De requetes Eloquent directes
- De manipulation de donnees complexe

**Doit contenir :**
- L'appel a l'Action appropriee
- La gestion de la reponse (redirect, vue, toast, erreur affichee)
- La capture des exceptions pour affichage utilisateur

```php
// Controller classique
class UserController extends Controller
{
    public function store(StoreUserRequest $request, CreateUserAction $action): RedirectResponse
    {
        try {
            $user = $action->execute($request->validated());

            return redirect()
                ->route('users.show', $user)
                ->with('toast-success', 'Utilisateur cree avec succes.');
        } catch (UserCreationException $e) {
            return redirect()
                ->back()
                ->with('toast-error', $e->getUserMessage());
        }
    }
}
```

```php
// Composant Livewire
class CreateUserForm extends Component
{
    public string $name = '';
    public string $email = '';

    public function save(CreateUserAction $action): void
    {
        $validated = $this->validate();

        try {
            $action->execute($validated);

            // La signature reelle depend du composant toast du projet
            $this->dispatch('toast', type: 'success', message: __('Utilisateur cree avec succes.'));
            $this->redirect(route('users.index'));
        } catch (UserCreationException $e) {
            $this->addError('user-creation-failed', $e->getUserMessage());
        }
    }
}
```

### 2. Action

**Role :** Orchestrateur single-responsibility. Une action = une operation metier complete. Coordonne l'appel a un ou plusieurs services et/ou repositories.

**Responsabilites :**
- Orchestrer les appels aux services et repositories dans le bon ordre
- Gerer les transactions de base de donnees quand plusieurs operations doivent etre atomiques
- Ne contient PAS de logique metier elle-meme — elle delegue

**Convention de nommage :** `{Verbe}{Entite}Action` — ex: `CreateUserAction`, `UpdateProjectAction`, `AssignRoleAction`

**Emplacement :** `app/Actions/{Domaine}/`

```php
namespace App\Actions\User;

use App\Services\User\UserCreationService;
use App\Services\Notification\WelcomeNotificationService;
use Illuminate\Support\Facades\DB;

class CreateUserAction
{
    public function __construct(
        private UserCreationService $userCreationService,
        private WelcomeNotificationService $welcomeNotificationService,
    ) {}

    /**
     * @param array{name: string, email: string, role?: string} $data
     */
    public function execute(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = $this->userCreationService->create($data);
            $this->welcomeNotificationService->send($user);

            return $user;
        });
    }
}
```

### 3. Service

**Role :** Contient la logique metier pure. Transforme, valide, calcule, applique les regles business.

**Responsabilites :**
- Logique metier (calculs, transformations, regles business)
- Appel aux repositories pour lire/ecrire des donnees
- Peut appeler d'autres services si necessaire

**Convention de nommage :** `{Entite}{Responsabilite}Service` — ex: `UserCreationService`, `InvoiceCalculationService`

**Emplacement :** `app/Services/{Domaine}/`

```php
namespace App\Services\User;

use App\Contracts\Repositories\User\UserWriteRepositoryInterface;

class UserCreationService
{
    public function __construct(
        private UserWriteRepositoryInterface $userWriteRepository,
    ) {}

    /**
     * @param array{name: string, email: string, role?: string} $data
     */
    public function create(array $data): User
    {
        $data['role'] = $data['role'] ?? 'member';
        $data['password'] = bcrypt(Str::random(16));

        return $this->userWriteRepository->create($data);
    }
}
```

### 4. Repository

**Role :** Seul point d'interaction avec la base de donnees. Encapsule toutes les requetes Eloquent.

**Responsabilites :**
- Requetes de lecture (find, list, search, count...)
- Requetes d'ecriture (create, update, delete...)
- Aucune logique metier

**Convention de nommage :** `{Entite}{Responsabilite}Repository` — ex: `UserReadRepository`, `UserWriteRepository`, `ProjectSearchRepository`

**Emplacement :** `app/Repositories/{Entite}/`

```php
namespace App\Repositories\User;

use App\Contracts\Repositories\User\UserReadRepositoryInterface;
use App\Exceptions\User\UserNotFoundException;
use App\Exceptions\User\UserListException;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

---

## Interfaces (Contrats)

Chaque repository a une interface bindee dans un ServiceProvider. Les interfaces sont segmentees par responsabilite : un repository = une interface. Pas de "fourre-tout".

**Emplacement :** `app/Contracts/Repositories/{Entite}/`

```php
namespace App\Contracts\Repositories\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserReadRepositoryInterface
{
    public function findById(int $id): User;

    public function listActive(): Collection;
}
```

```php
namespace App\Contracts\Repositories\User;

use App\Models\User;

interface UserWriteRepositoryInterface
{
    /**
     * @param array{name: string, email: string, password: string, role: string} $data
     */
    public function create(array $data): User;

    public function update(User $user, array $data): User;

    public function delete(User $user): void;
}
```

### Binding dans le ServiceProvider

```php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /** @var array<class-string, class-string> */
    public array $bindings = [
        \App\Contracts\Repositories\User\UserReadRepositoryInterface::class
            => \App\Repositories\User\UserReadRepository::class,
        \App\Contracts\Repositories\User\UserWriteRepositoryInterface::class
            => \App\Repositories\User\UserWriteRepository::class,
    ];
}
```

Le provider doit etre enregistre dans `bootstrap/providers.php`.

---

## Transactions

Utiliser `DB::transaction()` dans les Actions des que **plusieurs operations d'ecriture** doivent reussir ensemble ou echouer ensemble.

### Quand utiliser une transaction

| Situation | Transaction ? |
|-----------|--------------|
| Creer un user + envoyer un email | Non (l'email n'est pas une operation BDD) |
| Creer un user + creer son profil + assigner un role | **Oui** (3 ecritures BDD liees) |
| Mettre a jour un champ unique | Non |
| Transferer un montant entre deux comptes | **Oui** (debit + credit atomiques) |

### Pattern

```php
use Illuminate\Support\Facades\DB;

class TransferFundsAction
{
    public function execute(Account $from, Account $to, int $amount): void
    {
        DB::transaction(function () use ($from, $to, $amount) {
            $this->accountDebitService->debit($from, $amount);
            $this->accountCreditService->credit($to, $amount);
            $this->transactionLogService->log($from, $to, $amount);
        });
    }
}
```

La transaction est **toujours dans l'Action**, jamais dans le service ni le repository. L'Action est l'orchestrateur, c'est elle qui sait si l'operation est atomique.

---

## Accessors et attributs personnalises — Prudence

Les accessors et attributs personnalises Eloquent (`Attribute::make()`) sont une source frequente de problemes de performance et de bugs subtils. **Leur usage est strictement encadre.**

### Risques

1. **N+1 silencieux** — Un accessor qui execute une requete BDD (ex: `$this->pricingCells()->min('price')`) est invisible dans le code appelant. Quand il est appele dans une boucle (ex: affichage de 20 annonces dans le catalogue), il genere 20 requetes supplementaires sans que le developpeur s'en rende compte.

2. **Collision de noms** — Si un accessor porte le meme nom qu'une propriete du modele ou une relation, il masque l'original. Cela peut casser des sous-requetes optimisees qui s'attendent a acceder a la colonne ou relation directement.

### Regles

| Autorise | Interdit |
|----------|----------|
| Accessor qui formate/transforme des attributs deja charges (ex: formatage de date, concatenation de prenom/nom) | Accessor qui execute une requete BDD (`$this->relation()->min()`, `$this->relation()->count()`, etc.) |
| Accessor qui calcule a partir de proprietes en memoire | Accessor qui charge une relation non-eager-loadee |

### Alternative : sous-requete via repository

Pour les valeurs calculees qui necessitent une requete BDD (ex: prix minimum, nombre de reviews), utiliser une **sous-requete correlée** via `addSelect()` dans le repository :

```php
// Dans le repository (PAS dans le modele)
Listing::query()
    ->addSelect(['starting_price' => PricingCell::selectRaw('MIN(price)')
        ->join('pricing_columns', 'pricing_columns.id', '=', 'pricing_cells.pricing_column_id')
        ->whereColumn('pricing_columns.listing_id', 'listings.id')
    ])
    ->get();
```

Cette approche :
- Execute la valeur calculee dans la meme requete SQL (0 requetes supplementaires)
- Est explicite dans le code appelant (on voit le `addSelect` dans le repository)
- Ne risque pas de collision de noms avec les proprietes du modele
- S'optimise facilement (index, cache, etc.)

Les repositories sont l'endroit ideal pour encapsuler ces sous-requetes complexes — c'est precisement leur role dans l'architecture.

---

## Pragmatisme — Quand simplifier

L'architecture en couches est un guide, pas un dogme. **Le pragmatisme est une valeur fondamentale** : chaque couche doit justifier son existence par la complexite qu'elle encapsule. Creer une couche qui ne fait que passer un appel sans ajouter de valeur est du sur-engineering.

### Le repository : reserve aux cas complexes

Le repository est la couche la plus souvent sur-utilisee. **Ne pas creer de repository** pour des operations Eloquent triviales. Un repository qui ne fait que `Model::create($data)`, `Model::find($id)` ou `Model::query()->where(...)->get()` sans logique supplementaire n'apporte aucune valeur — il ajoute un fichier, une interface, un binding dans le ServiceProvider, le tout pour zero benefice.

**Creer un repository quand :**
- La requete implique du filtrage complexe, des scopes combines, des sous-requetes, ou des jointures
- La meme requete complexe est reutilisee a plusieurs endroits
- La logique de requete risque d'evoluer independamment de la logique metier
- On veut isoler des requetes specifiques pour les tester unitairement

**Ne PAS creer de repository quand :**
- L'operation est un simple CRUD sur un seul modele (`create`, `update`, `delete`, `find`)
- La requete est une lecture simple (`where` basique, `all`, `paginate`)
- Le repository ne ferait que "passer" l'appel au modele Eloquent sans rien ajouter

Dans ces cas, le service appelle directement Eloquent. C'est plus simple, plus lisible, et parfaitement acceptable.

### Quand sauter une couche

| Situation | Approche |
|-----------|----------|
| Interaction BDD triviale (CRUD simple, lecture basique) | Le service appelle Eloquent directement — pas de repository |
| L'action ne ferait qu'appeler un seul service sans transaction | Le controller/composant peut appeler le service directement |
| Un composant Livewire n'a qu'une requete simple a afficher | Appel Eloquent direct dans le `mount()` ou `render()` |
| Une page publique affiche des donnees statiques | Controller invocable simple, pas besoin de couches |

### Regles de decision

1. **1 operation simple, pas de logique** → appel inline (Eloquent direct)
2. **Logique metier sans complexite d'orchestration** → Service direct (pas d'Action)
3. **Orchestration de plusieurs services/repos, ou transaction** → Action complete
4. **Requete BDD avec logique de filtrage/recherche complexe** → Repository dedie

### Seuil de refactoring

Si un service ou un controller commence a accumuler de la logique (> 30-40 lignes dans une methode), c'est le signal pour extraire vers la couche inferieure. Mieux vaut commencer simple et extraire quand le besoin se presente, plutot que sur-architecturer des que la premiere operation.

### Principe directeur

**Quand on hesite entre "ajouter une couche" et "garder simple" : garder simple.** On peut toujours extraire vers une couche dediee plus tard quand la complexite le justifie. L'inverse (supprimer une couche devenue inutile) est plus couteux et arrive rarement.

---

## Autorisation et defense en profondeur

### Principe

L'authentification (middleware `lessor.auth`, `client.auth`, `admin.auth`) garantit que l'utilisateur est connecte. Mais elle ne garantit pas qu'il accede a **ses propres donnees**. L'autorisation doit etre verifiee a chaque couche qui manipule un identifiant utilisateur.

### Guard dans les Actions (defense en profondeur)

Toute Action qui recoit un `$lessorId`, `$clientId` ou identifiant utilisateur en parametre **doit verifier** que l'appelant est bien le proprietaire :

```php
class LoadDashboardChartDataAction
{
    public function execute(int $lessorId, ...): array
    {
        abort_unless(auth('lessor')->id() === $lessorId, 403);

        // ... logique
    }
}
```

Ce guard est une **seconde ligne de defense**. Meme si le composant Livewire passe deja `auth('lessor')->id()`, l'Action ne doit pas faire confiance a l'appelant. Si l'Action est reutilisee dans un autre contexte (API, admin, CLI), le guard empeche une fuite de donnees.

### Policies pour les operations d'ecriture

Les Policies Laravel sont definies dans `app/Policies/` et verifient l'ownership sur les modeles (`$listing->lessor_id === $lessor->id`). Elles **doivent etre appelees** avant toute operation de creation, modification ou suppression :

```php
// Dans le controller ou composant Livewire
$this->authorize('update', $listing);

// Ou dans une Action
Gate::authorize('update', [$listing]);
```

**Regle :** pas d'ecriture/suppression sans appel explicite a `$this->authorize()` ou `Gate::authorize()`. Les Policies ne servent a rien si elles ne sont pas invoquees.

### Resume

| Couche | Verification | Quand |
|--------|-------------|-------|
| Middleware | Authentification (est connecte ?) | Toujours (automatique via routes) |
| Action | `abort_unless(auth()->id() === $id)` | Toujours si l'Action recoit un identifiant utilisateur |
| Controller/Livewire | `$this->authorize('action', $model)` | Avant toute operation d'ecriture/suppression |
| Repository | Filtrage par `where('lessor_id', $id)` | Toujours dans les requetes |

---

## Arborescence type

```
app/
├── Actions/
│   ├── Auth/                              ← cross-cutting (pas lie a une zone)
│   │   ├── LoginAction.php
│   │   └── RegisterLessorAction.php
│   └── User/
│       ├── Admin/                         ← zone admin
│       │   └── ApproveListingAction.php
│       └── Lessor/                        ← zone loueur
│           ├── Listing/                   ← sous-domaine
│           │   ├── SaveListingAction.php
│           │   └── DeleteListingAction.php
│           ├── Rental/
│           │   └── MarkListingAsRentedAction.php
│           ├── Photo/
│           │   └── AddListingPhotoAction.php
│           └── Analytics/
│               └── LoadChartDataAction.php
├── Services/
│   ├── User/
│   │   └── Lessor/                        ← zone loueur
│   │       ├── Analytics/AnalyticsChartService.php
│   │       ├── Conversation/ConversationService.php
│   │       ├── Listing/ListingQueryService.php
│   │       └── Rental/RentalService.php
│   ├── GeocodingService.php               ← cross-cutting
│   └── ImageProcessingService.php         ← cross-cutting
├── Repositories/
│   └── User/
│       └── Lessor/                        ← zone loueur
│           ├── Analytics/AnalyticsReadRepository.php
│           ├── Conversation/ConversationReadRepository.php
│           └── Rental/RentalReadRepository.php
├── Contracts/
│   └── Repositories/
│       └── User/
│           └── Lessor/                    ← miroir des repositories
│               ├── Analytics/AnalyticsReadRepositoryInterface.php
│               ├── Conversation/ConversationReadRepositoryInterface.php
│               └── Rental/RentalReadRepositoryInterface.php
├── Exceptions/
│   ├── Listing/ListingException.php       ← par domaine metier
│   └── Rental/RentalException.php
└── Providers/
    └── RepositoryServiceProvider.php
```

### Principe de segmentation

**Le namespace reflete le contexte d'usage :**

- **Actions, Services, Repositories** : segmentes par **zone** (`User/Lessor/`, `User/Admin/`, `User/Client/`) puis par **sous-domaine** (`Listing/`, `Rental/`, `Analytics/`). Un `ConversationService` dans `User/Lessor/` sert le loueur ; le client aura le sien dans `User/Client/`.
- **Exceptions** : segmentees par **domaine metier** uniquement (`Listing/`, `Rental/`). Les exceptions sont partagees entre zones (une `RentalException` peut etre levee par le loueur ou l'admin).
- **Cross-cutting** : les services utilises par toute l'application (`GeocodingService`, `ImageProcessingService`) restent a la racine de `Services/`. Les actions d'authentification sont dans `Actions/Auth/`.

**Pourquoi cette segmentation :**
- Chaque sous-domaine est isole dans son propre namespace → fichiers plus petits, plus faciles a trouver
- L'ajout d'une zone (ex: `User/Client/`) ne pollue pas les zones existantes
- Un service au mauvais endroit se repere immediatement par son namespace
- Les IDE et l'autocomplétion beneficient de la hierarchie fine

**Quand un service est partage entre zones :** si une logique est strictement identique pour le loueur et le client, le service monte au niveau `Services/User/Shared/` ou `Services/Shared/` selon la portee. Mais **par defaut, chaque zone a ses propres fichiers** — on mutualise uniquement quand la duplication devient reelle, pas speculative.

→ Pour la structure de la couche presentation (controllers, composants Livewire, vues, assets), voir `rules/structure-fichiers.md`.

---

## Resume des conventions

### Couche presentation — segmentee par zone

| Couche | Emplacement | Nommage | Role |
|--------|-------------|---------|------|
| Controller | `app/Http/Controllers/{Zone}/` | `{Entite}Controller` | Point d'entree HTTP, delegation, reponse |
| Composant Livewire | `app/Livewire/{Zone}/` | `{Entite}Manager`, `{Entite}Form` | Interactivite, delegation, reponse |

`{Zone}` = zone fonctionnelle (ex: `User/Lessor`, `User/Admin`, `User/Client`, `Web`...).

### Couches metier — segmentees par zone puis sous-domaine

| Couche | Emplacement | Nommage | Role |
|--------|-------------|---------|------|
| Action | `app/Actions/{Zone}/{SousDomaine}/` | `{Verbe}{Entite}Action` | Orchestration, transactions |
| Service | `app/Services/{Zone}/{SousDomaine}/` | `{Entite}{Responsabilite}Service` | Logique metier |
| QueryService | `app/Services/{Zone}/{SousDomaine}/` | `{Entite}QueryService` | Lectures Eloquent complexes (listings pagines, recherches filtrees) quand un Repository + interface n'est pas justifie (pas de mock necessaire, pas d'exception metier a lever). Equivalent d'un read-model leger. Si un jour il faut mocker ou lever une exception typee, promouvoir en Repository. |
| Repository | `app/Repositories/{Zone}/{SousDomaine}/` | `{Entite}{Responsabilite}Repository` | Interaction BDD |
| Interface | `app/Contracts/Repositories/{Zone}/{SousDomaine}/` | `{Entite}{Responsabilite}RepositoryInterface` | Contrat du repository |
| Exception | `app/Exceptions/{Domaine}/` | `{Entite}{Contexte}Exception` | Erreur metier typee (partagee entre zones) |
| Provider | `app/Providers/` | `RepositoryServiceProvider` | Binding interfaces → implementations |

`{Zone}` = zone utilisateur (`User/Lessor`, `User/Admin`...). `{SousDomaine}` = domaine metier au sein de la zone (`Listing`, `Rental`, `Analytics`...). Les exceptions sont l'exception : elles sont organisees par domaine metier sans zone, car une meme erreur peut traverser plusieurs zones.

→ Pour la structure complete (controllers, vues, assets) et le detail de la segmentation par zone : voir `rules/structure-fichiers.md`.
