# Campus Inter - Mini-Application Web

Application web de gestion des offres de formations et préinscriptions étudiantes.

## Fonctionnalités

### Public
- Recherche dynamique de formations (filtres par niveau, domaine, spécialité, ville)
- Détail des formations avec établissements/campus disponibles
- Formulaire de préinscription multi-étapes
- Confirmation avec référence unique

### Administration
- Dashboard avec statistiques
- Gestion des domaines, spécialités, villes, établissements, campus
- Gestion des formations et années académiques
- Suivi des candidatures avec changement de statut
- Authentification sécurisée

## Architecture

```
campusinter-app/
├── public/          # Point d'entrée et assets
├── app/
│   ├── Controllers/ # Logique métier
│   ├── Models/      # Modèles de données
│   ├── Views/       # Templates PHP
│   ├── Helpers/     # Utilitaires (CSRF, Logger, etc.)
│   └── Mail/        # Envoi d'emails
├── config/          # Configuration
├── database/        # Migrations et seeds
└── storage/         # Logs et uploads
```

## Installation

### 1. Prérequis
- PHP 8.2+
- MySQL/MariaDB
- Composer

### 2. Installation des dépendances

```bash
cd campusinter-app
composer require phpmailer/phpmailer
```

### 3. Base de données

```bash
mysql -u root -p campus_inter < database/migrations/001_initial_schema.sql
mysql -u root -p campus_inter < database/seeds/001_demo_data.sql
```

### 4. Configuration

Copier et modifier le fichier de configuration :

```bash
cp config/config.php config/config.local.php
```

Ou définir les variables d'environnement :

```bash
export DB_HOST=localhost
export DB_NAME=campus_inter
export DB_USER=root
export DB_PASSWORD=votre_mot_de_passe
```

### 5. Serveur web

Configurer votre serveur Apache/Nginx pour pointer vers le dossier `public/`.

**Apache :** Le fichier `.htaccess` est déjà inclus.

**Nginx :**

```nginx
server {
    listen 80;
    server_name campusinter.com;
    root /chemin/vers/campusinter-app/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## Configuration Email

Pour l'envoi d'emails, configurer les paramètres SMTP :

```php
'MAIL_HOST' => 'smtp.gmail.com',
'MAIL_PORT' => 587,
'MAIL_USERNAME' => 'votre-email@gmail.com',
'MAIL_PASSWORD' => 'votre-mot-de-passe-app',
'MAIL_FROM' => 'noreply@campusinter.com',
'MAIL_TO' => 'admission@rabatam.ci.com',
```

## Compte Admin par défaut

- **Email :** admin@campusinter.com
- **Mot de passe :** Admin@123

**Important :** Changer ce mot de passe en production !

## Intégration Joomla

L'application peut être intégrée à un site Joomla en plaçant le dossier dans :

```
/joomla-root/campusinter-app/
```

Et en configurant un VirtualHost ou un sous-dossier pointant vers `public/`.

## Sécurité

- CSRF protection
- Rate limiting
- Honeypot anti-spam
- Requêtes préparées PDO
- Validation serveur
- Sessions sécurisées
- Logs d'activité

## Licence

Propriétaire - Campus Inter
