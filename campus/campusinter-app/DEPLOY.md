# CAMPUS INTER — Guide de déploiement InfinityFree

## Étape 1 : Créer un compte InfinityFree

1. Allez sur https://infinityfree.net
2. Créez un compte gratuit
3. Créez un nouvel hébergement (menu "Hébergements")
4. Notez les informations de connexion :
   - **Panel URL** : `https://panel.infinityfree.com`
   - **FTP Host** : `ftpupload.net` (ou similaire)
   - **FTP User** : fourni par InfinityFree
   - **FTP Password** : fournie à la création
   - **MySQL Host** : fourni (souvent `sql.infinityfree.com`)

## Étape 2 : Créer la base de données

1. Dans le panel InfinityFree, allez dans **MySQL Databases**
2. Créez une nouvelle base de données :
   - Nom : `campusinter`
   - Mot de passe : choisissez un mot de passe fort
3. Notez les informations :
   - **DB Host** : `sqlXXXinfinityfree.com`
   - **DB Name** : `if0_XXXXX_campusinter`
   - **DB User** : `if0_XXXXX`
   - **DB Password** : celle que vous avez choisie

## Étape 3 : Importer la base de données

1. Allez dans **phpMyAdmin** depuis le panel
2. Sélectionnez votre base de données
3. Cliquez sur l'onglet **Importer**
4. Choisissez le fichier `database/campusinter.sql`
5. Cliquez sur **Exécuter**

## Étape 4 : Upload des fichiers via FTP

1. Utilisez FileZilla ou tout client FTP
2. Connectez-vous avec les identifiants FTP
3. Uploadez **tout le contenu** du dossier `public/` à la racine du serveur :
   ```
   htdocs/          ← racine web
   ├── index.php
   ├── .htaccess
   ├── assets/
   │   ├── css/
   │   ├── js/
   │   └── img/
   └── ...
   ```
4. Uploadez aussi les dossiers `app/`, `config/`, `vendor/`, `storage/` **au-dessus** de la racine web (hors htdocs) si possible, ou dans un dossier sécurisé.

### Structure recommandée sur InfinityFree :
```
htdocs/              ← racine web (publique)
├── index.php
├── .htaccess
├── assets/
│   ├── css/
│   ├── js/
│   └── img/
├── app/
├── config/
├── vendor/
└── storage/
```

## Étape 5 : Configuration

Modifiez `config/config.php` avec vos informations :

```php
'DB_HOST' => 'if0_XXXXX.infinityfree.com',  // Votre DB host
'DB_NAME' => 'if0_XXXXX_campusinter',        // Votre DB name
'DB_USER' => 'if0_XXXXX',                     // Votre DB user
'DB_PASSWORD' => 'votre_mot_de_passe_db',     // Votre DB password
'APP_URL' => 'https://votre-site.infinityfree.com',  // Votre URL
```

## Étape 6 : Permissions

Sur InfinityFree, les permissions sont généralement bonnes par défaut.
Si vous avez des erreurs 500, essayez :
- `storage/` → chmod 755 ou 777
- `config/` → chmod 644

## Étape 7 : Test

1. Allez sur votre URL : `https://votre-site.infinityfree.com`
2. Vérifiez que la page d'accueil s'affiche
3. Testez la recherche de formations
4. Connectez-vous à l'admin : `/login`
   - Email : `admin@campusinter.com`
   - Mot de passe : `Admin@123`

## Limites InfinityFree (gratuit)

- **5 GB** de stockage
- **Bandwidth** : illimité (mais throttling possible)
- **PHP** : 8.x supporté
- **MySQL** : 1 base de données
- **Publicité** : une bannière en bas de page
- **Uptime** : 99.9%

## Fichiers importants

| Fichier | Rôle |
|---------|------|
| `database/campusinter.sql` | Dump de la base de données |
| `config/config.php` | Configuration (à modifier) |
| `public/.htaccess` | Réécriture d'URL Apache |
| `DEPLOY.md` | Ce guide |

## Debug

Si vous avez des erreurs :
1. Vérifiez les logs d'erreurs PHP dans le panel
2. Vérifiez que `APP_DEBUG` est à `true` temporairement
3. Vérifiez les permissions des fichiers
4. Vérifiez que la base de données est bien importée
