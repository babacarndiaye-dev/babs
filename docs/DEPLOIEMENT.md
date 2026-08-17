# Déploiement — Laravel Forge

Ce document décrit comment déployer EEHT sur un serveur Forge existant. Il ne
contient aucun secret — les valeurs sensibles (mots de passe, clé d'application,
identifiants SMTP) sont à saisir uniquement dans le panneau Forge (onglet
*Environment* du site), jamais dans le dépôt Git.

## 1. Prérequis côté serveur

- PHP 8.3+ (le projet compile aussi sous 8.4, utilisé en développement)
- MySQL 8
- Extensions PHP : `pdo_mysql`, `gd`, `mbstring`, `xml`, `curl`, `zip`, `dom`
  (toutes présentes par défaut sur un serveur Forge standard — aucune
  extension exotique requise, notamment **pas besoin d'Imagick** : les QR
  codes sont générés en SVG et les uploads d'images utilisent GD)
- Node 20+ et npm (pour `npm run build` pendant le déploiement)

## 2. Créer le site dans Forge

1. Dashboard Forge → serveur cible → **New Site**
2. Domaine : celui que vous avez choisi (ou l'IP du serveur en attendant un
   nom de domaine)
3. Type de projet : **General PHP / Laravel**
4. PHP version : 8.3 ou 8.4

## 3. Connecter le dépôt Git

Site → **Git Repository**
- Provider : GitHub
- Dépôt : `babacarndiaye-dev/babs`
- Branche : `main` (fusionner `claude/senegal-vocational-school-site-26lvix`
  dessus avant le premier déploiement, ou pointer directement sur cette
  branche si vous préférez déployer en continu depuis elle)
- **Install Composer Dependencies** : activé

## 4. Base de données

Site → **Database** (ou Serveur → **Database**) : créer une base MySQL et un
utilisateur dédié. Notez le nom de la base et les identifiants — ils vont dans
l'onglet *Environment*, pas dans le code.

## 5. Variables d'environnement (onglet *Environment*)

Partez de `.env.example` à la racine du dépôt et complétez au minimum :

```env
APP_NAME="EEHT"
APP_ENV=production
APP_KEY=                     # généré à l'étape 7, laisser vide ici
APP_DEBUG=false
APP_URL=https://votre-domaine.sn

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nom_de_la_base
DB_USERNAME=utilisateur_mysql
DB_PASSWORD=mot_de_passe_mysql

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync        # aucune notification n'est mise en file
                              # d'attente aujourd'hui — "sync" évite de
                              # devoir gérer un worker/daemon Forge

FILESYSTEM_DISK=local        # les pièces d'identité des candidats restent
                              # sur le disque privé du serveur — voir §8
                              # si vous voulez basculer vers S3 plus tard

MAIL_MAILER=smtp             # ou le fournisseur retenu (Postmark, SES...)
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS="no-reply@votre-domaine.sn"
MAIL_FROM_NAME="${APP_NAME}"
```

Les notifications WhatsApp/SMS restent des canaux placeholder (elles logguent
au lieu d'envoyer) tant qu'aucune passerelle n'est configurée — rien à
renseigner ici pour l'instant.

## 6. Script de déploiement

Site → **Deployment Script** : remplacer le script par défaut par celui-ci
(le fichier `deploy.sh` du dépôt contient exactement ce script, à titre de
référence versionnée) :

```bash
cd /home/forge/votre-domaine.sn

git pull origin $FORGE_SITE_BRANCH

$FORGE_COMPOSER install --no-dev --no-interaction --prefer-dist --optimize-autoloader

( flock -w 10 9 || exit 1
    echo 'Redémarrage FPM...'; sudo -S service $FORGE_PHP_FPM reload ) 9>/tmp/fpmlock

npm ci
npm run build

$FORGE_PHP artisan migrate --force
$FORGE_PHP artisan storage:link
$FORGE_PHP artisan config:cache
$FORGE_PHP artisan route:cache
$FORGE_PHP artisan view:cache
$FORGE_PHP artisan event:cache
```

## 7. Première mise en ligne (à faire une seule fois)

Depuis l'onglet **Commands** du site dans Forge (ou en SSH) :

```bash
php artisan key:generate --force
php artisan migrate --force
```

Pour peupler la base avec les référentiels (rôles/permissions, paramètres par
défaut, types de frais, modèles de documents) — **sans** les comptes de démo
français qui ne conviennent pas à un environnement de production :

```bash
php artisan db:seed --class=Database\\Seeders\\RolePermissionSeeder --force
php artisan db:seed --class=Database\\Seeders\\SettingsSeeder --force
php artisan db:seed --class=Database\\Seeders\\FinanceSeeder --force
php artisan db:seed --class=Database\\Seeders\\DocumentTemplateSeeder --force
```

Puis créez le premier compte administrateur via `php artisan tinker` :

```php
$user = \App\Models\User::create([
    'name' => 'Prénom Nom',
    'email' => 'admin@votre-domaine.sn',
    'password' => bcrypt('un-mot-de-passe-fort'),
    'is_active' => true,
]);
$user->assignRole('super-admin');
```

Ne lancez **jamais** `DemoSchoolSeeder` en production — il crée les comptes
de démonstration (`admin@eeht.sn`, mot de passe `password`, etc.) qui ne
doivent pas exister sur un site public.

## 8. SSL

Site → **SSL** → **LetsEncrypt** (gratuit, renouvellement automatique par
Forge) une fois le domaine pointé vers le serveur.

## 9. Stockage des pièces jointes en production

Par défaut, `FILESYSTEM_DISK=local` et les pièces d'identité des candidats
sont stockées sur le disque du serveur (`storage/app/private`), jamais
accessibles directement — uniquement via la route authentifiée
`/candidatures-pieces/{document}`. C'est suffisant pour un seul serveur.

Si vous passez plus tard à plusieurs serveurs (load balancing) ou voulez des
sauvegardes découplées du serveur, basculez ce disque vers S3 : ajoutez les
variables `AWS_*` dans l'environnement Forge, changez `FILESYSTEM_DISK=s3`,
et dans `app/Livewire/Candidate/ApplicationWizard.php` remplacez le second
argument `'local'` de `$file->store(...)` par `'s3'` (le contrôleur
`ApplicationDocumentController` fonctionne déjà avec n'importe quel disque
Laravel configuré, aucun autre changement de code n'est nécessaire).

## 10. Ce qui n'est volontairement pas mis en place

- **Pas de worker de file d'attente** : aucune notification n'implémente
  `ShouldQueue`, tout s'exécute en synchrone. Si vous ajoutez un jour des
  jobs en file d'attente, ajoutez un daemon Forge (`php artisan queue:work`)
  et repassez `QUEUE_CONNECTION` à `database`.
- **Pas de tâche planifiée (cron)** : `routes/console.php` ne définit
  aujourd'hui aucune tâche récurrente. Si vous en ajoutez une, activez le
  cron Forge standard (`* * * * * php artisan schedule:run`).
