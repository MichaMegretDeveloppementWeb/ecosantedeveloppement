# Conventions de nommage

## Principe fondamental

Tout ce qui est **interne au code** et a la base de donnees est en **anglais** : noms de classes, methodes, variables, proprietes, tables, colonnes, routes, fichiers PHP/Blade/CSS/JS, messages de commit, commentaires techniques.

Tout ce qui est **affiche a l'utilisateur** (labels, messages, textes d'interface, emails) est dans la langue du projet — definie par le contexte, pas par ce document.

**Objectif :** Des noms suffisamment explicites pour qu'un developpeur comprenne l'intention sans lire l'implementation, mais assez courts pour rester lisibles et manipulables.

---

## Regle d'equilibre : explicite sans exces

Un bon nom repond a la question "qu'est-ce que c'est ?" ou "qu'est-ce que ca fait ?" en un coup d'oeil.

| Trop court | Correct | Trop long |
|-----------|---------|-----------|
| `$u` | `$user` | `$currentlyAuthenticatedUser` |
| `$dt` | `$dueDate` | `$reminderDueDateTimestamp` |
| `calc()` | `calculateTotal()` | `calculateOrderTotalWithDiscountsAndTaxes()` |
| `$res` | `$response` | `$httpJsonApiResponse` |
| `chk()` | `isExpired()` | `checkIfSubscriptionHasExpired()` |
| `$tmp` | `$pendingOrders` | `$listOfOrdersThatAreCurrentlyPending` |

**Regles :**

1. **Pas d'abreviations** sauf celles universellement connues (`id`, `url`, `pdf`, `api`, `http`, `db`)
2. **Pas de redondance** avec le contexte — dans `UserService`, une methode `create()` suffit, pas besoin de `createUser()`
3. **Le type/role du conteneur dispense de le repeter** — `$users` dans un `UserRepository` n'a pas besoin de s'appeler `$userCollection`
4. **Preferer le specifique au generique** — `$activeUsers` plutot que `$data`, `$filteredResults` plutot que `$list`
5. **Un nom de methode commence par un verbe** — `send()`, `calculate()`, `findById()`, jamais un nom seul

---

## Classes PHP

Toutes les classes suivent le format **PascalCase**.

| Type | Pattern | Exemples |
|------|---------|----------|
| Modele | Singulier, nom de l'entite | `User`, `OrderItem`, `Invoice` |
| Controller | `{Entite}Controller` | `UserController`, `OrderController` |
| Composant Livewire | `{Entite}{Role}` | `UserManager`, `OrderForm`, `ConfirmPayment` |
| Action | `{Verbe}{Entite}Action` | `CreateUserAction`, `PublishArticleAction` |
| Service | `{Entite}{Responsabilite}Service` | `UserCreationService`, `InvoiceCalculationService` |
| Repository | `{Entite}{Responsabilite}Repository` | `UserReadRepository`, `OrderSearchRepository` |
| Interface | `{Classe}Interface` | `UserReadRepositoryInterface` |
| Exception | `{Entite}{Contexte}Exception` | `UserNotFoundException`, `PaymentFailedException` |
| Enum | Singulier, PascalCase | `OrderStatus`, `Priority`, `UserRole` |
| FormRequest | `{Verbe}{Entite}Request` | `StoreUserRequest`, `UpdateOrderRequest` |
| Middleware | Descriptif de l'action | `EnsureUserIsActive`, `RequireAdmin` |
| Mail | Descriptif du contenu | `OrderConfirmation`, `WelcomeNotification` |
| Event | Passe compose | `OrderPlaced`, `UserRegistered` |
| Listener | Descriptif de la reaction | `SendOrderConfirmation`, `NotifyAdmins` |
| Job | Imperatif | `ProcessPayment`, `GenerateReport` |
| Policy | `{Entite}Policy` | `UserPolicy`, `OrderPolicy` |

→ Pour le detail des patterns par couche (Actions, Services, Repositories) : voir `rules/architecture-solid.md`.
→ Pour l'organisation en fichiers et dossiers : voir `rules/structure-fichiers.md`.

---

## Methodes et fonctions

Format **camelCase**. Commencent toujours par un verbe.

### Verbes standards

| Verbe | Usage | Exemples |
|-------|-------|----------|
| `find` | Chercher un element (leve une exception si introuvable) | `findById()`, `findByEmail()` |
| `get` | Recuperer une valeur (retourne null si absente) | `getTotal()`, `getDefaultRole()` |
| `list` | Retourner une collection | `listActive()`, `listByCategory()` |
| `create` | Creer une nouvelle entite | `create()`, `createFromImport()` |
| `update` | Modifier une entite existante | `update()`, `updateStatus()` |
| `delete` | Supprimer une entite | `delete()`, `deleteExpired()` |
| `send` | Envoyer (email, notification...) | `send()`, `sendToAdmins()` |
| `calculate` | Effectuer un calcul | `calculateTotal()`, `calculateDiscount()` |
| `validate` | Verifier une regle | `validateQuantity()` |
| `format` | Transformer un format | `formatDate()`, `formatCurrency()` |
| `parse` | Analyser une donnee brute | `parseImportRow()` |
| `sync` | Synchroniser un etat | `syncPermissions()` |

### Booleens

Les methodes et proprietes booleennes utilisent un prefixe interrogatif :

| Prefixe | Usage | Exemples |
|---------|-------|----------|
| `is` | Etat de l'entite | `isActive()`, `isExpired()`, `$isPublished` |
| `has` | Possession/presence | `hasPermission()`, `hasDiscount()`, `$hasAttachments` |
| `can` | Capacite/autorisation | `canEdit()`, `canAccessReport()` |
| `should` | Decision logique | `shouldNotify()`, `shouldRetry()` |

**Regle :** Jamais de `getIsActive()` — utiliser directement `isActive()`.

---

## Variables et proprietes

Format **camelCase**.

### Regles

1. **Nommer d'apres ce que la variable contient**, pas d'apres comment elle est obtenue
2. **Collections au pluriel**, elements singuliers : `$users` (collection), `$user` (un seul)
3. **Pas de prefixe de type** : `$userName` et non `$strUserName`
4. **Pas de `$temp`, `$data`, `$result`, `$item`** sauf dans un scope tres local (closure d'une ligne)
5. **Les compteurs et iterateurs** courts sont acceptables dans les boucles : `$i`, `$key`, `$value`

| Mauvais | Correct | Pourquoi |
|---------|---------|----------|
| `$data` | `$orderDetails` | Specifique |
| `$arr` | `$categories` | Explicite |
| `$flag` | `$isVerified` | Semantique |
| `$user2` | `$recipient` | Le role, pas le numero |
| `$getAllUsers` | `$activeUsers` | Contenu, pas methode d'obtention |

---

## Base de donnees

### Tables

- **snake_case**, **pluriel** : `users`, `order_items`, `activity_logs`
- Le nom decrit le contenu : une table d'utilisateurs s'appelle `users`, pas `tbl_users` ni `user_data`
- **Pas de prefixe** (`tbl_`, `t_`, `app_`)

### Colonnes

- **snake_case**, **singulier** : `first_name`, `total_amount`, `created_at`
- **Booleens** avec prefixe `is_`, `has_`, `can_` : `is_active`, `has_discount`, `can_edit`
- **Dates** suffixees par `_at` ou `_date` : `published_at`, `due_date`, `expired_at`
- **Cles etrangeres** : `{table_singulier}_id` : `user_id`, `category_id`
- **Pas de redondance** avec le nom de la table : dans la table `users`, utiliser `first_name` et non `user_first_name`

### Tables pivot

- Noms des deux tables au singulier, ordre alphabetique, separes par `_` : `category_post`, `order_product`, `role_user`

### Indexes et contraintes

- Laravel genere des noms automatiques pour les indexes — les laisser sauf besoin specifique
- Contraintes unique nommees si multiples sur une table : `->unique(['email', 'tenant_id'], 'users_email_tenant_unique')`

### Migrations

- Nommer les fichiers de migration de facon descriptive : `create_users_table`, `add_status_to_orders_table`, `remove_legacy_columns_from_invoices_table`

---

## Enums

Cles en **PascalCase**. Les valeurs stockees en base sont en **snake_case** ou en **minuscules**.

```php
enum OrderStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}
```

**Regle :** Le nom de l'enum est singulier (`OrderStatus`, pas `OrderStatuses`).

---

## Routes

### URLs

- **kebab-case** pour les segments : `/order-items`, `/user-profile`, `/admin/activity-logs`
- **Pluriel** pour les ressources : `/users`, `/orders`, `/categories`
- **Pas de verbes dans l'URL** pour les ressources REST : `/users` (pas `/get-users` ni `/create-user`)

### Noms de routes

- **dot notation**, snake_case : `users.index`, `admin.orders.show`, `auth.login`
- Le prefixe correspond a la zone ou au groupe de routes : `admin.users.create`, `api.orders.store`

---

## Fichiers Blade

- **kebab-case** : `user-manager.blade.php`, `order-form.blade.php`
- Les vues principales s'appellent `index.blade.php`
- Les pages CRUD : `index`, `create`, `show`, `edit`
- Les partials ont un nom descriptif de leur contenu : `hero.blade.php`, `pricing-table.blade.php`

→ Pour l'organisation des fichiers Blade : voir `rules/structure-fichiers.md`.

---

## Constantes et configuration

- **Constantes PHP** en SCREAMING_SNAKE_CASE : `MAX_LOGIN_ATTEMPTS`, `DEFAULT_PAGINATION_SIZE`
- **Cles de configuration** en snake_case avec dot notation : `config('mail.default')`, `config('services.stripe.key')`
- **Variables d'environnement** en SCREAMING_SNAKE_CASE : `APP_NAME`, `DB_CONNECTION`, `MAIL_HOST`

---

## Commentaires et documentation

- **Commentaires dans le code** : en anglais
- **PHPDoc** : en anglais
- **Messages de commit** : en anglais
- **Documentation technique** (`docs/`) : dans la langue choisie pour le projet
- **Commentaires Blade** (`{{-- --}}`) : dans la langue choisie pour le projet (car proches de l'UI)

---

## Resume

| Element | Format | Langue | Exemple |
|---------|--------|--------|---------|
| Classe PHP | PascalCase | Anglais | `OrderItemService` |
| Methode | camelCase, verbe en tete | Anglais | `calculateDiscount()` |
| Variable / propriete | camelCase | Anglais | `$pendingOrders` |
| Constante | SCREAMING_SNAKE_CASE | Anglais | `MAX_RETRY_COUNT` |
| Table BDD | snake_case, pluriel | Anglais | `order_items` |
| Colonne BDD | snake_case, singulier | Anglais | `is_active` |
| Table pivot | singulier, alphabetique | Anglais | `category_post` |
| Enum key | PascalCase | Anglais | `InProgress` |
| Route URL | kebab-case, pluriel | Anglais | `/order-items` |
| Route name | dot.notation, snake_case | Anglais | `admin.orders.show` |
| Fichier Blade | kebab-case | Anglais | `user-manager.blade.php` |
| Fichier migration | snake_case descriptif | Anglais | `add_status_to_orders_table` |
| Cle config | snake_case, dot notation | Anglais | `services.stripe.key` |
| Variable d'env | SCREAMING_SNAKE_CASE | Anglais | `MAIL_HOST` |
| Texte affiche (UI) | — | Langue du projet | — |
