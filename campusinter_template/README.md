# Campus Inter - Template Joomla

## Installation

1. Compresser le dossier `campusinter_template` en `campusinter_template.zip`
2. Dans le backend Joomla, aller dans **Système** → **Installer** → **Installer un template**
3. Télécharger le fichier ZIP et installer
4. Aller dans **Extensions** → **Templates** → **Styles**
5. Définir "Campus Inter Template" comme template par défaut

## Structure des fichiers

```
campusinter_template/
├── templateDetails.xml    # Manifest du template
├── index.php              # Page d'accueil principale
├── component.php          # Pages de composants (articles, etc.)
├── error.php              # Pages d'erreur (404, etc.)
├── offline.php            # Page de maintenance
├── css/
│   └── template.css       # Styles CSS avec animations
├── js/
│   └── scripts.js         # JavaScript interactif
├── images/
│   └── (voir README-images.txt)
└── html/
    ├── mod_menu/
    │   └── default.php    # Override du menu principal
    ├── mod_custom/
    │   └── default.php    # Override des modules personnalisés
    └── mod_footer/
        └── default.php    # Override du module footer
```

## Configuration requise

- Joomla 4.x ou 5.x
- PHP 7.4 ou supérieur
- Module "Menu" publié dans la position `header`
- Module "Personnaliser" publié dans la position `banner` (optionnel)

## Positions de modules

| Position | Description |
|----------|-------------|
| `header` | Menu principal |
| `banner` | Contenu de la bannière hero |
| `services` | Section services |
| `stats` | Statistiques clés |
| `partners` | Section partenaires |
| `faq` | Section FAQ |
| `footer` | Modules du pied de page |

## Personnalisation

### Couleurs
Les couleurs peuvent être modifiées dans le gestionnaire de templates :
- Couleur principale : Bleu foncé (#0a2540)
- Couleur d'accent : Orange (#f39c12)

### Images à ajouter dans le dossier `images/`
- `logo.png` - Logo Campus Inter (hauteur recommandée : 56px)
- `hero-bg.jpg` - Image de fond de la bannière (1920x1080px)
- `stats-bg.jpg` - Image de fond de la section statistiques (optionnel)

## Compatibilité

- Navigateurs modernes : Chrome, Firefox, Safari, Edge (dernières versions)
- Responsive : Bureau, Tablette, Mobile
- Accessibilité : Supporte `prefers-reduced-motion`

## Support

Pour toute question, contactez : contact@campusinter.com

---

Campus Inter - Votre partenaire mobilité internationale
Bè-Kpota, face à la mosquée, Lomé, Togo
Tel : +228 22 70 25 96 / +228 97 75 40 00
