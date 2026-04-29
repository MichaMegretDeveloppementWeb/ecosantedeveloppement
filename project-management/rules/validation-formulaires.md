# Validation des formulaires

## Principe

Chaque formulaire doit avoir une validation **exhaustive** : toutes les regles necessaires ET un message d'erreur explicite pour chaque regle de chaque champ. Aucun message par defaut du framework ne doit etre affiche tel quel a l'utilisateur.

Si l'application est multi-langue, chaque message de validation doit etre une phrase complete enveloppee dans `__()` via la methode `messages()`. Pour une application mono-langue, `validationAttributes()` + `messages()` pour les cas specifiques reste une approche acceptable.

---

## Composants Livewire (formulaires interactifs)

La validation se fait via les methodes `rules()` et `messages()` du composant.

### Strategie de messages

Laravel offre deux mecanismes complementaires pour personnaliser les messages de validation :

1. **`validationAttributes()`** — Definit le nom lisible de chaque champ. Laravel l'injecte automatiquement dans les messages generiques (`:attribute est obligatoire` → `Le nom est obligatoire`).

2. **`messages()`** — Definit un message explicite pour chaque regle de chaque champ.

**Approche selon le contexte :**

- **Application mono-langue :** `validationAttributes()` en priorite, `messages()` uniquement pour les cas ou le message generique n'est pas assez clair.
- **Application multi-langue (cas de VantaDrive) :** Utiliser **`messages()` avec des phrases completes enveloppees dans `__()`** pour chaque regle de chaque champ. Les patterns generiques comme `:attribute est obligatoire` produisent des traductions incorrectes dans les langues dont la grammaire differe du francais (article, genre, position du sujet). Les phrases completes garantissent un message naturel et correct dans toutes les langues.

### Structure obligatoire (application multi-langue)

```php
class CreateUserForm extends Component
{
    public string $name = '';
    public string $email = '';

    /** @return array<string, string|array<string>> */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        ];
    }

    /**
     * Messages explicites pour chaque regle — phrases completes
     * enveloppees dans __() pour la traduction.
     *
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'name.required' => __('Le nom est obligatoire.'),
            'name.min' => __('Le nom doit contenir au moins :min caractères.'),
            'name.max' => __('Le nom ne peut pas dépasser :max caractères.'),
            'email.required' => __('L\'adresse email est obligatoire.'),
            'email.email' => __('L\'adresse email n\'est pas valide.'),
            'email.max' => __('L\'adresse email ne peut pas dépasser :max caractères.'),
            'email.unique' => __('Cette adresse email est déjà utilisée.'),
        ];
    }

    public function save(CreateUserAction $action): void
    {
        $this->validate();

        // ...
    }
}
```

### Structure alternative (application mono-langue)

Pour une application mono-langue, `validationAttributes()` reste acceptable en complement de `messages()` pour les cas specifiques :

```php
protected function validationAttributes(): array
{
    return [
        'name' => 'nom',
        'email' => 'adresse email',
    ];
}

protected function messages(): array
{
    return [
        'email.unique' => 'Cette adresse email est déjà utilisée.',
    ];
}
```

### Fichiers de traduction

Pour VantaDrive (multi-langue), les messages de validation sont des cles francaises dans le code, traduites dans les fichiers JSON :

```json
// lang/en.json (extrait)
{
    "Le nom est obligatoire.": "Name is required.",
    "L'adresse email est obligatoire.": "Email address is required.",
    "Le mot de passe doit contenir au moins :min caractères.": "Password must be at least :min characters."
}
```

Les placeholders `:min`, `:max` sont preserves a travers `__()` et substitues par le validateur Laravel.

`lang/fr.json` reste vide car les cles sont deja en francais. `lang/fr/validation.php` existe toujours pour les messages generiques du framework (en dehors des composants Livewire).

### Regles

1. **Pour les applications multi-langue, `messages()` avec phrases completes enveloppees dans `__()`** — garantit des messages naturels et corrects dans toutes les langues. Pour les applications mono-langue, `validationAttributes()` + `messages()` pour les cas specifiques reste acceptable.
2. **Un message explicite pour chaque regle de chaque champ.** L'utilisateur ne doit jamais voir un message par defaut du framework non personnalise.
3. **`rules()` retourne un array** avec les regles sous forme de tableau (pas de string pipe `|`).
4. **Valider avec `$this->validate()`** dans la methode d'action (save, update, etc.), pas en temps reel sauf cas specifique.

### Validation en temps reel (cas specifiques)

Pour les champs qui beneficient d'un feedback instantane (ex: unicite d'un email), utiliser `$this->validateOnly()` via un hook `updated` :

```php
public function updatedEmail(): void
{
    $this->validateOnly('email');
}
```

Ne pas abuser de la validation en temps reel. L'utiliser uniquement quand le feedback immediat apporte une reelle valeur (unicite, format complexe).

### Validation avec wire:model

Preferer `wire:model.blur` pour valider au blur du champ. Utiliser `wire:model.live.debounce.300ms` uniquement pour la recherche.

---

## Controllers classiques (FormRequest)

Pour les formulaires traites par un controller classique (non-Livewire), utiliser systematiquement des **FormRequest** dediees.

### Structure obligatoire

```php
namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ou logique d'autorisation
    }

    /** @return array<string, array<string>> */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:admin,member,viewer'],
            'bio' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Noms lisibles des champs.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => __('validation.attributes.name'),
            'email' => __('validation.attributes.email'),
            'role' => __('validation.attributes.role'),
            'bio' => __('validation.attributes.bio'),
        ];
    }

    /**
     * Messages personnalises (uniquement si le message generique ne suffit pas).
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.unique' => __('validation.custom.email.unique'),
            'role.in' => __('validation.custom.role.in'),
        ];
    }
}
```

> **Note :** Dans un FormRequest, la methode s'appelle `attributes()` (pas `validationAttributes()` comme dans Livewire). Les deux jouent le meme role.

### Convention de nommage

| Operation | Classe | Emplacement |
|-----------|--------|-------------|
| Creation | `Store{Entite}Request` | `app/Http/Requests/{Domaine}/` |
| Mise a jour | `Update{Entite}Request` | `app/Http/Requests/{Domaine}/` |

### Regles identiques a Livewire

1. Pour les applications multi-langue, `messages()` avec phrases completes enveloppees dans `__()`. Pour les applications mono-langue, `attributes()` + `messages()` pour les cas specifiques.
2. Un message explicite pour chaque regle de chaque champ.
3. Regles sous forme de tableau (pas de string pipe).
4. Jamais de validation inline dans le controller.

---

## Affichage des erreurs de validation en vue

Les exemples ci-dessous illustrent le pattern d'affichage. **Les classes CSS et composants utilises sont a titre d'illustration** — adapter au design system du projet.

### Erreurs inline sous les champs (Livewire)

```blade
<div>
    <label for="name">Nom</label>
    <input type="text" id="name" wire:model.blur="name"
        class="{{ $errors->has('name') ? 'erreur-visuelle' : 'style-normal' }}" />
    @error('name')
        <p class="message-erreur">{{ $message }}</p>
    @enderror
</div>
```

**Principes (independants du design system) :**

- Chaque champ affiche son erreur **directement en dessous** via `@error('champ')`
- Le champ en erreur a un **style visuel distinct** (bordure rouge, icone, etc.) — adapte au design system
- La valeur saisie est **preservee** apres erreur (`wire:model` ou `old()`)

### Erreurs inline sous les champs (Blade classique)

```blade
<div>
    <label for="name">Nom</label>
    <input type="text" id="name" name="name" value="{{ old('name') }}"
        class="{{ $errors->has('name') ? 'erreur-visuelle' : 'style-normal' }}" />
    @error('name')
        <p class="message-erreur">{{ $message }}</p>
    @enderror
</div>
```

---

## Checklist validation

Avant de considerer un formulaire comme termine :

- [ ] Chaque champ a toutes ses regles de validation definies
- [ ] `messages()` definit un message explicite pour chaque regle de chaque champ
- [ ] Si multi-langue : chaque message est enveloppe dans `__()` et traduit dans `lang/{locale}.json`
- [ ] Les erreurs s'affichent inline sous chaque champ concerne
- [ ] Les champs en erreur ont un style visuel distinct (adapte au design system)
- [ ] La valeur saisie est preservee apres erreur (`old()` ou `wire:model`)
