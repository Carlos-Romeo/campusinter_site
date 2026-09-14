# 🚀 Guide de Déploiement - Campus Inter

## Déploiement sur Render

### 1. Pré-requis
- Un compte [Render](https://render.com)
- Un repository Git (GitHub/GitLab)
- Les clés API Timoney (pour les paiements)

### 2. Configuration du déploiement

#### Variables d'environnement à configurer sur Render :

| Variable | Description | Exemple |
|----------|-------------|---------|
| `APP_ENV` | Environnement | `production` |
| `APP_URL` | URL de votre site | `https://campusinter.onrender.com` |
| `TIMONEY_API_KEY` | Clé API Timoney | `xxxxx` |
| `TIMONEY_API_SECRET` | Secret API Timoney | `xxxxx` |
| `MAIL_USERNAME` | Email SMTP | `gmail.com` |
| `MAIL_PASSWORD` | Mot de passe app SMTP | `xxxxx` |

### 3. Déploiement automatique

1. Connectez votre repository à Render
2. Render détectera automatiquement le `render.yaml`
3. Le déploiement se lance automatiquement

### 4. Vérification post-déploiement

Une fois déployé, vérifiez :
- [ ] La page d'accueil charge correctement
- [ ] Les filtres de formation fonctionnent
- [ ] Le formulaire de pré-inscription fonctionne (4 étapes)
- [ ] L'upload de documents fonctionne
- [ ] La redirection vers le paiement fonctionne
- [ ] Le panel admin est accessible (`/login`)

## Déploiement local (Docker)

### 1. Construction de l'image

```bash
docker build -t campus-inter .
```

### 2. Exécution

```bash
docker run -p 8080:80 campus-inter
```

### 3. Accès

- Application : http://localhost:8080
- Admin : http://localhost:8080/login
- Identifiants : `admin@campusinter.com` / `Admin@123`

## Migration de la base de données

Les migrations s'exécutent automatiquement au démarrage du conteneur.

Pour exécuter manuellement :
```bash
docker exec -it <container_id> mysql -u root campusinter < /var/www/database/migrations/002_add_documents_and_payments.sql
```

## Configuration des paiements

### Timoney (Orange Money / MTN Mobile Money)

1. Créez un compte sur [Timoney](https://timoney.com)
2. Obtenez vos clés API (API_KEY et API_SECRET)
3. Configurez-les dans les variables d'environnement
4. En développement, le paiement est simulé automatiquement

### Intégration en production

Le système est prêt pour l'intégration Timoney. Il suffit de :
1. Configurer les variables d'environnement
2. Le code utilisera automatiquement l'API réelle en production

## Structure des fichiers

```
campusinter-app/
├── app/
│   ├── Controllers/
│   │   ├── PaymentController.php    # Gestion paiements
│   │   └── ...
│   └── Views/
│       ├── public/
│       │   ├── payment.php          # Page de paiement
│       │   └── receipt.php          # Reçu
│       └── admin/
│           └── payments/            # Gestion admin
├── database/
│   └── migrations/
│       └── 002_*.sql               # Migration paiements
├── docker/
│   └── start.sh                    # Script démarrage
├── render.yaml                     # Config Render
└── Dockerfile                      # Image Docker
```

## Troubleshooting

### Erreur 500
- Vérifiez les logs sur Render
- Vérifiez que la migration a bien été exécutée

### Upload de fichiers ne fonctionne pas
- Vérifiez les permissions du dossier `storage/uploads`
- Vérifiez la taille maximale d'upload dans PHP

### Paiement ne fonctionne pas
- En développement : le paiement est simulé
- En production : vérifiez les clés API Timoney

## Support

Pour toute assistance, contactez : admission@rabatam.ci.com
