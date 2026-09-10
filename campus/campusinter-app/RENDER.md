# CAMPUS INTER — Déploiement sur Render

## Prérequis

1. Un compte GitHub (pour le code)
2. Un compte Render (gratuit) : https://render.com

## Étape 1 : Pousser le code sur GitHub

```bash
cd /home/it01/Bureau/campus\ inter/campus/campusinter-app
git init
git add .
git commit -m "Initial commit - Campus Inter"
git remote add origin https://github.com/VOTRE-USER/campusinter-app.git
git push -u origin main
```

## Étape 2 : Créer un compte Render

1. Allez sur https://render.com
2. Cliquez sur **"Get Started for Free"**
3. Connectez votre compte GitHub

## Étape 3 : Créer un Web Service

1. Dans le dashboard Render, cliquez sur **"New +"** → **"Web Service"**
2. Connectez votre dépôt GitHub
3. Configurez :
   - **Name** : `campus-inter`
   - **Runtime** : `Docker`
   - **Dockerfile Path** : `./Dockerfile`
   - **Plan** : `Free`
4. Cliquez sur **"Create Web Service"**

## Étape 4 : Attendre le déploiement

Le build prend 3-5 minutes. Render :
1. Construit l'image Docker
2. Installe PHP, Apache, MySQL
3. Importe la base de données
4. Démarre l'application

## Étape 5 : Tester

Une fois déployé, vous aurez une URL comme :
`https://campus-inter.onrender.com`

### Pages disponibles

| URL | Description |
|-----|-------------|
| `/` | Accueil + recherche |
| `/login` | Connexion admin |
| `/admin` | Back-office |

### Identifiants admin

- **Email** : `admin@campusinter.com`
- **Mot de passe** : `Admin@123`

## Limites Render Free

- **512 MB RAM** (suffisant pour cette app)
- **Build time** : 500 min/mois
- **Sleep après 15 min** d'inactivité (premier accès = 30s de délai)
- **HTTPS** inclus automatiquement

## Variables d'environnement

Dans le dashboard Render, allez dans **"Environment"** pour ajouter :

| Clé | Valeur |
|-----|--------|
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_URL` | `https://campus-inter.onrender.com` |

## Fichiers créés

| Fichier | Rôle |
|---------|------|
| `Dockerfile` | Image Docker (PHP + Apache + MySQL) |
| `docker/apache.conf` | Configuration Apache |
| `docker/start.sh` | Script de démarrage |
| `render.yaml` | Config Render |
| `.dockerignore` | Fichiers exclus du build |

## Dépannage

### Erreur 500
- Vérifiez les logs dans Render → Logs
- Vérifiez que la base de données est importée

### Sleep (délai au démarrage)
- Normal sur le plan free
- L'app se "réveille" automatiquement

### Build échoué
- Vérifiez que le Dockerfile est correct
- Vérifiez les logs de build dans Render
