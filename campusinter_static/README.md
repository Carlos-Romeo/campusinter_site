# Campus Inter - Site Statique

## Description

Site web statique moderne pour **Campus Inter**, agence togolaise spécialisée dans la mobilité internationale des étudiants. Design inspiré de FIGS Education, avec animations, responsive design et contenu complet.

## Structure du projet

```
campusinter_static/
├── index.html                  # Page d'accueil
├── a-propos.html               # Page À propos / Qui sommes-nous
├── services.html               # Page Nos services (détaillée)
├── partenaires.html            # Page Nos partenaires
├── contact.html                # Page Contact avec formulaire
├── mentions-legales.html       # Page Mentions légales
├── politique-confidentialite.html  # Page Politique de confidentialité
├── cgv.html                    # Page Conditions Générales de Vente
├── css/
│   └── template.css            # Styles CSS principaux (2000+ lignes)
├── js/
│   └── scripts.js              # JavaScript interactif
└── images/
    ├── logo.png                # Logo Campus Inter
    ├── logo.svg                # Logo vectoriel
    ├── hero-bg.jpg             # Image de fond bannière
    ├── hero_bg.jpg             # Image de fond bannière (variante)
    ├── visa_study.jpg          # Image visas/études
    ├── housing.jpg             # Image hébergement
    ├── gge_logo.svg            # Logo Galileo Global Education
    ├── mediaschool_logo.svg    # Logo MediaSchool
    ├── omnes_logo.svg          # Logo OMNES Éducation
    ├── mbn_logo.svg            # Logo MBN Global Education
    └── hema_logo.svg           # Logo Groupe HEMA
```

## Lancer le site en local

### Méthode 1 : Ouvrir directement dans le navigateur

1. Ouvrir le dossier `campusinter_static` dans l'explorateur de fichiers
2. Double-cliquer sur `index.html` pour ouvrir dans le navigateur par défaut

**Note** : Certaines fonctionnalités (comme le formulaire de contact et la carte Google Maps) nécessitent une connexion internet car elles chargent des ressources externes (Font Awesome, Google Fonts, Google Maps).

### Méthode 2 : Avec un serveur local (recommandé)

#### Option A : Python (si installé)

```bash
# Aller dans le dossier du site
cd "/home/it01/Bureau/campus inter/campusinter_static"

# Python 3
python3 -m http.server 8000

# Puis ouvrir dans le navigateur : http://localhost:8000
```

#### Option B : PHP (si installé)

```bash
# Aller dans le dossier du site
cd "/home/it01/Bureau/campus inter/campusinter_static"

# Démarrer le serveur PHP
php -S localhost:8000

# Puis ouvrir dans le navigateur : http://localhost:8000
```

#### Option C : Node.js (si installé)

```bash
# Installer un serveur HTTP simple
npm install -g http-server

# Aller dans le dossier du site
cd "/home/it01/Bureau/campus inter/campusinter_static"

# Démarrer le serveur
http-server -p 8000

# Puis ouvrir dans le navigateur : http://localhost:8000
```

#### Option D : Extension VS Code "Live Server"

1. Ouvrir le dossier `campusinter_static` dans VS Code
2. Installer l'extension "Live Server" si ce n'est pas déjà fait
3. Clic droit sur `index.html` → "Open with Live Server"

## Pages du site

| Page | URL | Description |
|------|-----|-------------|
| Accueil | `index.html` | Bannière hero, services, statistiques, partenaires, FAQ |
| À propos | `a-propos.html` | Histoire, mission, valeurs, chiffres clés |
| Services | `services.html` | 6 services détaillés avec fonctionnalités |
| Partenaires | `partenaires.html` | 5 groupes partenaires avec écoles associées |
| Contact | `contact.html` | Formulaire, coordonnées, carte Google Maps |
| Mentions légales | `mentions-legales.html` | Informations légales et éditeur |
| Confidentialité | `politique-confidentialite.html` | Politique RGPD |
| CGV | `cgv.html` | Conditions générales de vente |

## Fonctionnalités incluses

- ✅ Design moderne et responsive (mobile, tablette, desktop)
- ✅ Menu fixe avec effet de scroll
- ✅ Menu mobile hamburger avec overlay
- ✅ Bannière hero plein écran avec animation
- ✅ Animations au scroll (fade-in, slide)
- ✅ Compteurs animés pour les statistiques
- ✅ Cartes de services avec effets hover
- ✅ Section partenaires avec logos
- ✅ FAQ accordéon interactif
- ✅ Formulaire de contact avec validation JavaScript
- ✅ Carte Google Maps intégrée
- ✅ Footer complet avec coordonnées et réseaux sociaux
- ✅ Accessibilité (focus visible, reduced motion)
- ✅ Optimisations de performance

## Personnalisation

### Couleurs

Les couleurs principales sont définies dans les variables CSS (`:root`) :

```css
--primary-color: #0a2540;    /* Bleu foncé */
--accent-color: #f39c12;     /* Orange */
```

### Logo

Remplacer `images/logo.png` par le vrai logo de Campus Inter.

### Images

- `images/hero-bg.jpg` : Photo de fond pour la bannière (1920x1080px recommandé)
- `images/logo.png` : Logo Campus Inter (hauteur recommandée : 56px)
- Logos des partenaires : déjà présents en format SVG

### Coordonnées

Modifier les coordonnées dans les fichiers HTML (téléphone, email, adresse) selon les informations réelles de Campus Inter.

## Technologies utilisées

- HTML5 sémantique
- CSS3 (Grid, Flexbox, animations, variables CSS)
- JavaScript vanilla (ES6+)
- Font Awesome 6.4.0 (icônes)
- Google Fonts (Poppins + Open Sans)
- Google Maps Embed API

## Compatibilité

- Chrome (dernière version)
- Firefox (dernière version)
- Safari (dernière version)
- Edge (dernière version)
- Mobile : iOS Safari, Chrome Mobile

## Support

Pour toute question : contact@campusinter.com

---

**Campus Inter** - Votre partenaire mobilité internationale  
Bè-Kpota, face à la mosquée, Lomé, Togo  
Tel : +228 22 70 25 96 / +228 97 75 40 00
