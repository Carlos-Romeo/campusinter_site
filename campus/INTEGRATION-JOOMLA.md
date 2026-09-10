# Campus Inter — finition premium Joomla

Les dix pages HTML chargent maintenant deux ressources communes :

- `images/campusinter/campus-inter-common.css`
- `images/campusinter/campus-inter-premium.js`

Le footer est centralisé à la racine du site Joomla : `footer.html`, `footer.css` et `footer.js`. Déposez ces trois fichiers à la racine publique du site afin que le chemin `/footer.js` puisse charger le composant partagé.

Conservez ces deux fichiers dans le même dossier `images/campusinter/` lors de l'import dans le gestionnaire de médias Joomla. Les liens étant déjà présents dans chaque page, aucune autre modification n'est nécessaire.

`campus-inter-common.css` porte le socle partagé : charte, typographie, boutons, préchargeur, bandeau de contact et finition premium. Les styles restants dans les pages sont propres à leurs sections métier.

Avant mise en ligne, remplacez les liens sociaux encore réglés sur `#` par les URL officielles de Campus Inter.
