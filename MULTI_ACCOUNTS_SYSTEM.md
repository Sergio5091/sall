# Système de Gestion Multi-Comptes Promoteurs

## Vue d'ensemble

Ce système permet aux promoteurs de gérer plusieurs comptes promoteurs et de basculer entre eux sans avoir à se reconnecter à chaque fois.

## Architecture

### Base de données

1. **Table `promoter_accounts`** (table pivot) :
   - `main_user_id`: ID du compte promoteur principal
   - `linked_user_id`: ID du compte promoteur lié
   - `is_active`: Booléen indiquant si c'est le compte actif actuellement
   - `nickname`: Nom d'affichage personnalisé pour le compte lié
   - Contrainte unique: Un promoteur ne peut avoir qu'un seul compte actif à la fois

### Modèles

#### User
- `linkedPromoterAccounts()`: Relation many-to-many avec les comptes liés (si principal)
- `mainPromoterAccount()`: Relation avec le compte principal (si lié)
- `getAllPromoterAccounts()`: Récupère tous les comptes (principal + liés)
- `getActivePromoterAccount()`: Récupère le compte actif actuel
- `setActivePromoterAccount($userId)`: Définit le compte actif
- `addPromoterAccount($userId, $nickname)`: Ajoute un compte lié
- `removePromoterAccount($userId)`: Retire un compte lié
- `isMainPromoter()`: Vérifie si c'est un compte principal
- `isLinkedPromoter()`: Vérifie si c'est un compte lié
- `getMainAccount()`: Obtient le compte principal

### Contrôleurs

#### AccountSwitchController
- `index()`: Affiche la page de gestion des comptes
- `switch()`: Effectue le changement de compte actif
- `addAccount()`: Ajoute un nouveau compte lié
- `removeAccount()`: Retire un compte lié
- `updateNickname()`: Met à jour le surnom d'un compte
- `getActiveAccount()`: API pour obtenir le compte actif
- `getAccounts()`: API pour obtenir tous les comptes

### Middleware

#### SetActivePromoterAccount
- Gère automatiquement le compte actif en session
- Définit automatiquement le compte principal comme actif si aucun n'est défini

### Interface Utilisateur

#### Composants Vue
- **AccountSelector.vue**: Composant dropdown pour sélectionner le compte actif
- **AccountSwitch.vue**: Page complète de gestion des comptes

## Utilisation

### Pour le promoteur principal

1. **Ajout de comptes**:
   - Le compte principal peut ajouter d'autres comptes promoteurs
   - Saisir l'email du compte à lier
   - Optionnel: définir un surnom pour l'affichage
   - Le compte doit exister et être de rôle "promoter"

2. **Switching entre comptes**:
   - Via le sélecteur de compte dans l'interface
   - Via la page dédiée `/promoter/accounts`
   - Le changement est instantané, pas besoin de se reconnecter

3. **Gestion**:
   - Chaque compte conserve ses propres salles et événements
   - Le surnom peut être modifié à tout moment
   - Les comptes peuvent être retirés (ne supprime pas le compte, seulement le lien)

### Pour le compte lié

- Peut voir qu'il est lié à un compte principal
- Ne peut pas ajouter/retirer d'autres comptes
- Accède normalement à ses propres fonctionnalités

### Pour le développeur

#### Routes ajoutées
```php
// Routes pour la gestion des comptes promoteurs
Route::get('/accounts', [AccountSwitchController::class, 'index'])->name('accounts.index');
Route::post('/accounts/switch/{accountId}', [AccountSwitchController::class, 'switch'])->name('accounts.switch');
Route::post('/accounts/add', [AccountSwitchController::class, 'addAccount'])->name('accounts.add');
Route::delete('/accounts/{accountId}', [AccountSwitchController::class, 'removeAccount'])->name('accounts.remove');
Route::patch('/accounts/{accountId}/nickname', [AccountSwitchController::class, 'updateNickname'])->name('accounts.update-nickname');
Route::get('/api/active-account', [AccountSwitchController::class, 'getActiveAccount'])->name('api.active-account');
Route::get('/api/accounts', [AccountSwitchController::class, 'getAccounts'])->name('api.accounts');
```

#### Utilisation dans les vues
```vue
<!-- Sélecteur de compte -->
<AccountSelector />

<!-- Lien vers la page de gestion -->
<Link :href="route('promoter.accounts.index')">Gérer mes comptes</Link>
```

#### Accès au compte actif dans les contrôleurs
```php
$user = Auth::user();
$mainAccount = $user->isMainPromoter() ? $user : $user->getMainAccount();
$activeAccount = $mainAccount->getActivePromoterAccount();
$allAccounts = $mainAccount->getAllPromoterAccounts();
```

## Flux de travail

### 1. Création du système
- Un promoteur existant est automatiquement considéré comme "compte principal"
- Il peut ajouter d'autres comptes promoteurs existants

### 2. Ajout d'un compte
- Le compte principal accède à la page de gestion
- Il saisit l'email d'un autre compte promoteur
- Le système vérifie que le compte existe et n'est pas déjà lié
- Le compte est ajouté avec un surnom optionnel

### 3. Switching
- Le promoteur sélectionne un compte dans le sélecteur
- Le système met à jour la session et la base de données
- L'interface se rafraîchit avec le nouveau contexte

### 4. Gestion continue
- Les comptes peuvent être activés/désactivés
- Les surnoms peuvent être modifiés
- Les comptes peuvent être retirés du groupe

## Sécurité

- Seul le compte principal peut ajouter/retirer des comptes
- Un compte ne peut être lié qu'à un seul compte principal
- Vérification systématique des permissions dans les contrôleurs
- La session stocke uniquement l'ID du compte actif

## Avantages

1. **Flexibilité**: Gestion centralisée de plusieurs comptes promoteurs
2. **Simplicité**: Un seul login pour plusieurs comptes
3. **Performance**: Switching instantané sans reconnexion
4. **Organisation**: Surnoms personnalisés pour une meilleure identification
5. **Sécurité**: Contrôle d'accès granulaire

## Cas d'usage

- **Agences de gestion d'événements**: Gérer plusieurs clients promoteurs
- **Entreprises multi-salles**: Différents gestionnaires pour différentes salles
- **Franchises**: Gestion centralisée des comptes des franchisés
- **Partenariats**: Partager l'accès entre plusieurs promoteurs

## Notes importantes

- Les comptes liés restent indépendants pour leurs données
- Le retrait d'un compte ne supprime pas le compte lui-même
- Le système utilise principalement la table pivot pour la gestion
- Les sessions sont utilisées pour stocker temporairement le compte actif
- Le middleware garantit qu'un compte est toujours actif si le promoteur en possède plusieurs

## Limitations

- Un compte lié ne peut pas devenir compte principal
- Un compte ne peut être lié qu'à un seul compte principal
- Le compte principal ne peut pas être supprimé tant qu'il a des comptes liés
