# CAMPUS INTER — Spécification UX/UI & Design System
## Cahier de design détaillé — Identité visuelle + composants + spécifications techniques

> **Version** : 1.0 · **Date** : 13/08/2026
> **Projet** : Refonte du site Joomla de Campus Inter (agence de mobilité internationale, Bè-Kpota, Lomé)
> **Documents liés** : `ROADMAP-PROMPT-CAMPUS-INTER.md`, `accueil.html`
> **Type de document** : Référence unique pour les maquettes, l'intégration et le contrôle qualité.

---

## TABLE DES MATIÈRES

1. Philosophie de design
2. Identité visuelle (logo, couleurs, typographie, grille, motifs)
3. Design tokens (variables CSS techniques)
4. Système de composants UI
5. Layout page par page (wireframes détaillés)
6. Animations & micro-interactions
7. États des composants (hover, focus, erreur, loading…)
8. Responsive & breakpoints
9. Accessibilité (WCAG 2.1 AA)
10. Performance & SEO
11. Intégration technique Joomla
12. Checklist qualité

---

## 1. PHILOSOPHIE DE DESIGN

### 1.1 Positionnement
Campus Inter se positionne comme l'**agence de mobilité internationale de référence à Lomé** : rassurante, professionnelle, ambitieuse et moderne. La promesse est « **L'Immigration No Stress !** » : le design doit donc **inspirer confiance** (propreté, ordre, transparence) tout en étant **dynamique et aspirant** (mouvement, dégradés, animations).

### 1.2 Principes directeurs
| Principe | Traduction concrète |
|---|---|
| **Confiance** | Beaucoup d'espace blanc, alignement strict, données chiffrées visibles |
| **Dynamisme** | Dégradés verts, animations au scroll, cartes 3D, marquee partenaires |
| **Clarté** | Hiérarchie typographique forte, un seul CTA principal par écran |
| **Chaleur humaine** | Photos de vrais étudiants, témoignages, tons verts chaleureux |
| **Premium** | Ombres douces, coins arrondis 12–20 px, micro-interactions soignées |

### 1.3 Personnalités utilisateurs cibles
- **L'étudiant (18–28 ans)** : mobile-first, recherche des formations, veut un visuel qui inspire.
- **Le jeune professionnel** : recherche la procédure visa travail, veut de la clarté et des étapes.
- **Le parent** : cherche de la réassurance, des contacts directs (téléphone, WhatsApp).
- **Le partenaire** : vérifie la crédibilité de l'agence (groupes partenaires, chiffres).

### 1.4 Ton de la voix visuel
Moderne, francophone, énergique mais sérieux. Le vert évoque la croissance, l'Europe et la réussite. Le noir anthracite apporte le sérieux institutionnel. Le blanc garantit la lisibilité.

---

## 2. IDENTITÉ VISUELLE

### 2.1 Logotype
- **Nom** : CAMPUS INTER (deux mots, éventuellement « CAMPUS » en noir + « INTER » en vert, ou inverse).
- **Logo fourni** : `campus-inter_logo-2026_fonds-blanc.jpg` (à retravailler en version PNG transparente).
- **Déclinaisons** :
  - Version couleur (fonds blanc ou clairs) — usage principal.
  - Version monochrome blanche (fonds sombres, header hero, footer).
  - Version favicon : monogramme « CI » ou globe/avion dans un carré arrondi vert `#159447`.
- **Zone de protection** : hauteur de logo × 0,25 autour de chaque côté.
- **Taille minimale** : 120 px de large (desktop), 96 px (mobile).
- **Interdits** : ne pas déformer, ne pas changer les couleurs, ne pas mettre sur fond vert sans encadré.

### 2.2 Palette de couleurs (charte officielle)

**Couleurs primaires (bloquantes)**
| Rôle | Nom | Hex | RGB | Usage |
|---|---|---|---|---|
| Vert principal | `--ci-vert` | `#159447` | 21,148,71 | CTA primaires, liens, icônes, dégradés |
| Noir / anthracite | `--ci-noir` | `#252525` | 37,37,37 | Titres, header, footer, sections sombres |
| Blanc | `--ci-blanc` | `#FFFFFF` | 255,255,255 | Fonds, textes sur sections sombres |

**Déclinaisons du vert (obligatoires pour la profondeur)**
| Rôle | Hex | Usage |
|---|---|---|
| Vert foncé | `#0F7A3A` | Texte sur fond clair, survol des CTA, hover liens |
| Vert clair | `#1DB85E` | Points d'accent, compteurs, éléments sur fond sombre |
| Vert fond très clair | `#E8F7EE` | Fonds de cartes, badges, survols |
| Vert fond cartes | `#DDF0E5` | Survol des features |

**Couleurs neutres**
| Rôle | Hex | Usage |
|---|---|---|
| Fond gris-vert | `#F4F7F4` | Sections alternées, fond du site |
| Fond très clair | `#F8FAF8` | Arrière-plan global du contenu |
| Texte principal | `#2F3B36` | Paragraphes |
| Texte secondaire | `#6B7280` | Sous-titres, métadonnées, légendes |
| Bordure | `#E7EFE8` | Cartes, séparateurs, inputs |
| Bordures légères | `#E2E8E2` | Inputs |

**Couleurs d'accent fonctionnelles**
| Rôle | Hex | Usage |
|---|---|---|
| Succès / WhatsApp | `#25D366` | Bouton WhatsApp flottant |
| Étoiles | `#F5B301` | Notes des témoignages |
| Erreur | `#DC2626` | Messages de validation |
| Alerte | `#F59E0B` | Notices |
| Info | `#3B82F6` | Informations |

**Dégradés officiels**
- `linear-gradient(135deg, #159447, #1DB85E)` — CTA primaires, badges, icônes.
- `linear-gradient(135deg, #252525, #159447)` — sections CTA, hero (overlay photo).
- `linear-gradient(120deg, rgba(37,37,37,.92), rgba(21,148,71,.55))` — overlay hero.

**Ratios d'utilisation (équilibre)**
- ~60 % blanc / fonds clairs
- ~25 % vert (accent)
- ~15 % noir (structure)

**Contraste (vérifié pour AA)**
- Vert `#159447` sur blanc : ratio ≈ 4,5:1 → **OK pour grands textes & composants**.
- Vert `#0F7A3A` sur blanc : ratio ≈ 6,5:1 → **OK texte normal**.
- Blanc sur vert `#159447` : ratio ≈ 4,5:1 → **OK grands textes (boutons)**.
- Blanc sur noir `#252525` : ratio ≈ 16:1 → **Excellent**.
- Texte `#6B7280` sur blanc : ratio ≈ 4,6:1 → **OK petits textes**.
- Vert clair `#1DB85E` sur noir : ratio ≈ 6,5:1 → **OK texte sur fond sombre**.

### 2.3 Typographie

**Police titres — « Plus Jakarta Sans »** (Google Fonts)
| Style | Poids | Taille | Usage |
|---|---|---|---|
| Display 1 | 800 | clamp(2.4rem,5.5vw,4rem) | H1 hero |
| Display 2 | 800 | clamp(1.7rem,3.6vw,2.6rem) | H2 sections |
| H3 | 700 | 1.15–1.2rem | Titres de cartes |
| H4 | 700 | 1.0–1.08rem | Sous-titres, items |
| Kicker (sur-titre) | 700 | 0.82rem · lettrage 0.14em · majuscules | Étiquettes de section |

**Police corps — « Inter »** (Google Fonts)
| Style | Poids | Taille | Usage |
|---|---|---|---|
| Corps | 400 | 1rem / 1.65 | Paragraphes |
| Corps léger | 400 | 0.93–0.95rem | Descriptions de cartes |
| Label | 600 | 0.78–0.82rem | Labels de formulaire |
| Méta | 400–500 | 0.82rem | Dates, catégories |
| Petit | 400 | 0.8rem | Footer, mentions |

**Règles typographiques**
- Interligne titres : 1.2 · Corps : 1.65.
- Largeur de ligne idéale : 60–75 caractères.
- Éviter plus de 3 niveaux de hiérarchie par écran.
- Les nombres (statistiques) : Plus Jakarta Sans 800, avec le symbole `+` ou `%` dans la même couleur accent.

**Fallbacks** : `sans-serif` (Arial/Helvetica) si les polices Google ne chargent pas.

### 2.4 Grille & mise en page
- **Conteneur** : `width: min(1180px, 92%)` · marges 4 % sur mobile.
- **Grille** : 12 colonnes (desktop) → 6 (tablette) → 4/1 (mobile).
- **Gouttières** : 24 px (desktop), 16 px (mobile).
- **Espacement vertical des sections** : 90 px desktop · 64 px mobile.
- **Alignement** : textes de sections centrés ; contenu long justifié à gauche ; liste alignée à gauche.
- **Sections alternées** : blanc ↔ `#F4F7F4` pour structurer la page.

### 2.5 Rayons, ombres & bordures
| Élément | Valeur |
|---|---|
| Rayon des boutons | 999 px (pill) |
| Rayon des cartes | 18–22 px |
| Rayon des inputs | 12 px |
| Rayon des badges | 999 px |
| Ombre carte | `0 8px 26px rgba(0,0,0,.06)` |
| Ombre carte survol | `0 14px 40px rgba(21,148,71,.14)` |
| Ombre bouton primaire | `0 14px 40px rgba(21,148,71,.25)` → `0 18px 45px rgba(21,148,71,.35)` au survol |
| Ombre header sticky | `0 8px 30px rgba(0,0,0,.08)` |
| Ombre méga-menu | `0 25px 60px rgba(0,0,0,.14)` |
| Bordures | 1px `#E7EFE8` · inputs 1.5px `#E2E8E2` · focus 2px `#159447` |

### 2.6 Icônes
- **Style** : SVG inline, trait 2 px (stroke), coins arrondis (style Lucide).
- **Taille** : 16–18 px (boutons/liens), 24–26 px (icônes d'items), 34 px (features).
- **Couleur** : vert `#159447` sur fond clair, vert clair `#1DB85E` ou blanc sur fond sombre.
- **Pictogrammes métier** : livre/diplôme (études), valise (travail), tampon/passeport (visa), maison (installation), globe (international), avion (mobilité).
- **Interdits** : icônes de banques différentes non uniformisées, emojis dans les composants d'interface (les emojis ne restent que dans le contenu éditorial).

### 2.7 Imagerie
- **Style photo** : photos réelles, lumineuses, prises naturelles (étudiants, campus, villes européennes, passeport/visa, logement).
- **Traitement** : légère saturation verte pour harmoniser ; overlay dégradé vert/noir sur les héros.
- **Formats** : WebP (JPG en fallback). Ratio des visuels de cartes : 16:9 ou 4:3.
- **Rôles** : hero (grande image), cartes services (visuel), partenaires (logos blancs sur fond noir), témoignages (avatars en monogramme).
- **Alt obligatoire** sur chaque image (descriptif, ex. « Élève souriant devant un campus à Paris »).

### 2.8 Motifs & textures
- **Wave divider** : SVG `M0,32L60,37.3...` entre sections sombres et claires (hauteur 70 px).
- **Particules** : cercles blancs flottants animés dans le hero (taille 8–18 px, opacité .25).
- **Marquee** : bandeau défilant de logos partenaires avec `mask-image` (fondu aux bords).
- **Dégradés de fond** : pour les sections CTA et le hero uniquement (ne pas surcharger).

---

## 3. DESIGN TOKENS (VARIABLES CSS TECHNIQUES)

```css
:root{
  /* Couleurs */
  --ci-vert:#159447;  --ci-vert-fonce:#0F7A3A;  --ci-vert-clair:#1DB85E;
  --ci-vert-bg:#E8F7EE; --ci-vert-bg-2:#DDF0E5;
  --ci-noir:#252525;  --ci-blanc:#FFFFFF;
  --ci-fond:#F4F7F4;  --ci-fond-2:#F8FAF8;
  --ci-texte:#2F3B36; --ci-texte-2:#6B7280;
  --ci-bordure:#E7EFE8; --ci-bordure-input:#E2E8E2;

  /* Accents fonctionnels */
  --ci-whatsapp:#25D366; --ci-etoiles:#F5B301;
  --ci-erreur:#DC2626;   --ci-alerte:#F59E0B; --ci-info:#3B82F6;

  /* Dégradés */
  --ci-grad:linear-gradient(135deg,#159447,#1DB85E);
  --ci-grad-sombre:linear-gradient(135deg,#252525,#159447);

  /* Rayons */
  --ci-rayon-sm:12px; --ci-rayon:18px; --ci-rayon-lg:22px; --ci-pill:999px;

  /* Ombres */
  --ci-ombre-carte:0 8px 26px rgba(0,0,0,.06);
  --ci-ombre:0 14px 40px rgba(21,148,71,.14);
  --ci-ombre-forte:0 18px 45px rgba(21,148,71,.35);
  --ci-ombre-noir:0 14px 40px rgba(21,148,71,.25);
  --ci-ombre-header:0 8px 30px rgba(0,0,0,.08);
  --ci-ombre-mega:0 25px 60px rgba(0,0,0,.14);

  /* Typo */
  --ci-font-titres:'Plus Jakarta Sans',sans-serif;
  --ci-font-texte:'Inter',sans-serif;

  /* Espacement vertical des sections */
  --ci-section-pad:90px; --ci-section-pad-mobile:64px;

  /* Layout */
  --ci-conteneur:min(1180px,92%);
  --ci-gouttiere:24px; --ci-gouttiere-mobile:16px;

  /* Transitions */
  --ci-t-fast:.25s; --ci-t-moyen:.35s; --ci-t-lent:.6s;
  --ci-courbe:cubic-bezier(.25,.8,.25,1);
  --ci-rebond:cubic-bezier(.7,0,.3,1);
}
```

> **Implémentation** : ces tokens vont dans `templates/[template]/css/custom.css` (Joomla). Le CSS des pages `.ci-` consomme uniquement ces variables.

---

## 4. SYSTÈME DE COMPOSANTS UI

### 4.1 Boutons
| Type | Style | États |
|---|---|---|
| **Primaire** | Fond dégradé vert, texte blanc, pill, ombre verte | Hover : `translateY(-3px)` + ombre forte + overlay dégradé sombre ; Active : `translateY(-1px)` |
| **Secondaire (outline)** | Transparent, bordure 2px noir, texte noir | Hover : fond noir, texte blanc |
| **Blanc (sur fond sombre)** | Fond blanc, texte vert foncé | Hover : `translateY(-3px)` + ombre |
| **Fantôme (lien)** | Texte vert + flèche `→` | Hover : flèche glisse de 5 px |
| **WhatsApp** | Vert WhatsApp `#25D366`, icône | Pulse ring animé |

- **Hauteur** : 50 px (15 px de padding vertical, 30 px horizontal).
- **Icône** : optionnelle, 18 px, translate-X au survol.
- **Touche clavier** : focus visible = ring 2 px `#159447` offset 2 px.

### 4.2 Formulaires
| Élément | Spécification |
|---|---|
| Input / select | Fond `#FBFDFB`, bordure 1.5px `#E2E8E2`, rayon 12 px, padding 13–14 px |
| Label | `.78rem`, 600, majuscules espacées (sur les widgets) |
| Placeholder | `#6B7280` |
| Focus | Bordure 2px `#159447`, pas d'ombre (ou ring léger vert) |
| Erreur | Bordure `#DC2626` + message sous le champ + `aria-describedby` |
| Succès | Bordure `#159447` + coche |
| Checkbox RGPD | Carré 20 px, coché = fond vert + coche blanche |
| Bouton submit | Style bouton primaire, centré, pleine largeur sur mobile |

**Validation JS** : en temps réel (perte de focus), messages français précis (« Merci de saisir une adresse email valide »), `required` + `aria-invalid`.

### 4.3 Cartes (modèle générique)
```
┌─────────────────────────────┐
│ [image 16:9, zoom au hover] │
├─────────────────────────────┤
│  (badge icône flottant)     │
│  TITRE (H3)                 │
│  Description (0.93rem)      │
│  Lien "En savoir plus →"    │
└─────────────────────────────┘
```
- Badge : 46 px, dégradé vert, position absolue à cheval sur l'image.
- Hover : `translateY(-6/8px)`, ombre verte, image scale(1.08).

### 4.4 Badges & kickers
- **Kicker** : pill vert clair sur fond `#E8F7EE`, texte vert `#0F7A3A`, 700.
- **Kicker fond sombre** : texte `#8EE0B4`, fond rgba blanc .12.
- **Catégorie blog** : texte 0.75rem 700 majuscules vert.
- **Badge hero** : fond blanc .12 + bordure .25 + blur, texte blanc.

### 4.5 Accordéons (FAQ)
- Item : carte blanche, bordure 1px, rayon 16 px, ombre quand ouvert.
- Question : bouton 100 %, flex, icône `+` dans cercle 30 px qui pivote à 45°.
- Réponse : `max-height` animé 0.4 s, texte `#6B7280`.
- Un seul item ouvert à la fois (accordéon exclusif).

### 4.6 Slider / carrousel
- Cartes de 1 à l'écran (desktop), autoplay 5 s, pause au survol.
- Navigation : flèches rondes 46 px (bordure verte, fond vert au hover) + points 10 px (actif = vert, scale 1.3).
- Respect `prefers-reduced-motion` (désactive l'autoplay).

### 4.7 Navigation
**Topbar** (noir, 8 px vertical) : téléphone, WhatsApp, email, horaires à gauche ; réseaux à droite. Masqué sur très petit écran (réseaux).
**Header sticky** : blanc, ombre au scroll ; logo 44 px ; liens avec soulignement animé vert (scaleX 0→1) ; bouton CTA.
**Méga-menu** : carte 560 px, 2 colonnes, bordure haute 4 px verte, items avec icône + titre + sous-titre. Apparaît au survol avec `translate(-50%,14px→6px)`.
**Menu mobile** : plein écran noir, liens 1.4rem 700, bordure basse, fermeture `×` en haut à droite, slide depuis la droite (0.45 s).

### 4.8 Footer
- Fond noir `#1c1c1c` (variante du noir officiel pour distinguer du header).
- 4 colonnes : À propos + logo + réseaux | Nos services | Liens rapides | Contact.
- Titres blancs avec barre 36 px dégradé vert sous le titre.
- Liens : hover translateX(4px) + vert clair.
- Bandeau bas : copyright, mentions, politique, FAQ.

### 4.9 Widget « Trouver ma formation »
- Carte blanche, rayon 20 px, ombre forte `0 30px 70px rgba(0,0,0,.28)`.
- 2 selects + 1 bouton primaire pleine hauteur.
- Desktop : `grid-template-columns: 2fr 2fr 1fr`. Mobile : 1 colonne.

### 4.10 Timeline
- Ligne verticale 4 px `#E8F7EE` centrée (desktop) / à gauche 12 px (mobile).
- Points : cercle 22 px, bordure 5 px verte, anneau `#E8F7EE` (offset 6 px).
- Numéros « Étape 01 » verts 700. Contenu alterné gauche/droite.

### 4.11 Marquee partenaires
- Fond noir. Track flex `gap:50px`, `animation: 28s linear infinite`, duplication du contenu ×2 pour la boucle, pause au survol.
- Logos : hauteur 34 px, `filter: brightness(0) invert(1)` (blancs), opacity .85.
- `mask-image: linear-gradient(90deg, transparent, #000 12%, #000 88%, transparent)`.

### 4.12 Statistiques
- Fond noir, grille auto-fit (min 200 px), nombres 2.6rem 800 vert clair, libellés `#C7D2CB`.
- Compteur : animation 1.8 s, easing cubic-bezier, déclenché à 50 % de visibilité.

### 4.13 Boutons flottants
- **WhatsApp** : 60 px, bas-droit, fond `#25D366`, ring pulsant.
- **Retour en haut** : 48 px, bas-droit au-dessus du WhatsApp (bottom 100 px), noir → vert au hover, apparaît après 600 px de scroll.

---

## 5. LAYOUT PAGE PAR PAGE (WIREFRAMES DÉTAILLÉS)

### 5.1 Accueil (ordre des sections)
1. Préloader (logo + barre, 3 s max)
2. Topbar + Header sticky + progress bar
3. **Hero** : badge → H1 → typewriter → sous-titre → 2 CTA → widget recherche → scroll indicator → particules
4. **Stats** (noir) : 5 compteurs
5. **Engagements** : 5 items checklist
6. **Domaines d'expertise** (fond `#F4F7F4`) : 6 cartes tilt
7. **Comment ça marche** : timeline 4 étapes
8. **Partenaires** (noir) : titre + marquee + bouton
9. **Pourquoi nous** : 4 features
10. **Témoignages** (fond `#F4F7F4`) : slider 3
11. **FAQ** : accordéon 5 questions
12. **Blog** (fond `#F4F7F4`) : 3 cartes
13. **CTA final** (dégradé sombre) : H2 + texte + formulaire rapide + wave
14. **Footer**
15. WhatsApp flottant + retour en haut

### 5.2 Étudier en France
1. Hero interne (image + fil d'Ariane) avec parallax
2. « Pourquoi étudier en France » : 5 cartes
3. « Notre accompagnement complet » : 4 étapes numérotées + phrase « Étudier en France devient simple et sécurisé »
4. Processus détaillé : timeline 6 étapes
5. Écoles & programmes : grille filtrable par domaine (8 domaines)
6. Témoignage étudiant France
7. FAQ visa étudiant
8. CTA + formulaire

### 5.3 Étudier en Belgique
1. Hero + fil d'Ariane
2. « Pourquoi la Belgique » : 4–6 cartes
3. Processus d'inscription : timeline
4. Types de démarches (attestation, équivalence, visa)
5. FAQ
6. CTA

### 5.4 Travailler en France
1. Hero + fil d'Ariane
2. « Des opportunités réelles » : 5 cartes secteurs (BTP, Santé, Restauration, Industrie, Services)
3. « Notre méthode » : 7 étapes checklist
4. Bandeau promesse « Migration légale et encadrée »
5. Métiers en tension (listes par secteur)
6. Procédure visa travail (étapes + documents)
7. FAQ
8. CTA

### 5.5 Visa & Démarches
1. Hero
2. Types de visas : 5 cartes
3. Nos services : icônes (éligibilité, dossier, rendez-vous, assurance, suivi)
4. Checklist documents (liste à puces avec coches)
5. Processus timeline
6. FAQ
7. CTA

### 5.6 Installation en France
1. Hero
2. Logement : 3 items
3. Formalités : assurance, banque, inscription, CAF
4. Accompagnement à l'arrivée : 3 étapes
5. Témoignage
6. CTA

### 5.7 Écoles & Programmes (+ fiche école)
1. Hero + recherche/filtres (domaine, pays, niveau)
2. Grille d'écoles filtrable (façon FIGS) : logo, nom, groupe, campus, diplômes, « Voir la fiche »
3. **Fiche école** : bandeau école, présentation, formations, campus, admission, « Je candidate »
4. Domaines mis en avant
5. CTA « Trouver ma formation »

### 5.8 Nos Partenaires
1. Hero « Un réseau solide pour votre réussite »
2. Intro texte
3. **5 fiches académiques** (alternées gauche/droite image) : logo, description, chiffres, écoles clés, bouton
4. Partenaires financiers : 3 items
5. Partenaires logement : 3 items
6. Valeur ajoutée : 3 items
7. CTA

### 5.9 Qui sommes-nous
1. Hero
2. Notre mission : 4 items
3. Nos valeurs : 4 cartes
4. Pourquoi nous : 4 points
5. Chiffres clés : compteurs
6. Notre équipe : 3–4 cartes
7. Témoignages
8. Citation « Votre réussite est notre priorité »
9. CTA

### 5.10 Blog + Article
1. Hero + catégories filtrables (Études, Visa, Emploi, Logement)
2. Grille d'articles (image, catégorie, titre, date, extrait)
3. Pagination (style : pill, actif = vert)
4. **Article** : bandeau, corps (H2/H3, listes, encadrés), partage social, article précédent/suivant, CTA

### 5.11 Contact
1. Hero
2. Coordonnées : 5 cartes (adresse, fixe, mobile, email, horaires)
3. Carte Google Maps (iframe Bè-Kpota)
4. Formulaire « Démarrer mon projet » (7 champs + RGPD)
5. Formulaire contact simple (Nom, Email, Sujet, Message)
6. Boutons flottants

### 5.12 Pages complémentaires
- **FAQ** : accordéons par catégorie.
- **Merci** : grande animation coche ✓ verte, « Merci ! Votre demande a bien été envoyée. Notre équipe vous recontacte sous 48h. », boutons retour + WhatsApp.
- **404** : avion animé + « Page introuvable » + lien retour accueil.
- **Mentions légales / CGU** : texte 0.95rem, H2/H3 hiérarchisés, ancres.

---

## 6. ANIMATIONS & MICRO-INTERACTIONS

| # | Animation | Durée | Easing | Déclencheur | Notes |
|---|---|---|---|---|---|
| 1 | Préloader (barre) | 1.2 s (boucle) | ease-in-out | Chargement | Disparaît en 0.6 s fondu |
| 2 | Fade-up hero | 0.7 s, délais 0.2/0.35/0.5/0.65 | cubic-bezier | Load | Cascade sur les éléments hero |
| 3 | Typewriter | 80 ms/lettre, pause 1.6 s | step | Load | Mots : Étudier / Travailler / Visa / S'installer |
| 4 | Particules | 10–16 s (boucle) | linear | Load | Montée verticale |
| 5 | Reveal scroll | 0.7 s | ease | Intersection 15 % | Classes `.ci-reveal`, `.ci-reveal-left/right` |
| 6 | Compteurs | 1.8 s | cubic-bezier ease-out | Visible 50 % | De 0 → cible |
| 7 | Marquee | 28 s (boucle) | linear | Load | Pause au survol |
| 8 | Tilt 3D | 0.15 s (temps réel) | — | Mousemove | Rotation max 8°, perspective 900 px |
| 9 | Boutons magnétiques | temps réel | — | Mousemove | Déplacement 0.2 × distance |
| 10 | Soulignement menu | 0.3 s | ease | Hover | scaleX 0→1 |
| 11 | Méga-menu | 0.3 s | ease | Hover | translate + fade |
| 12 | Slider témoignages | 0.6 s (fade) + 5 s autoplay | ease | Load | Points + flèches |
| 13 | Accordéon | 0.4 s | ease | Click | max-height fluide, icône 45° |
| 14 | Scroll progress | temps réel | — | Scroll | Largeur = % lecture |
| 15 | Hover cartes | 0.3 s | ease | Hover | translateY + ombre + zoom image |
| 16 | Wave | — | — | — | SVG statique |
| 17 | WhatsApp pulse | 1.8 s (boucle) | ease-out | Load | Ring scale 0.85→1.4 |
| 18 | Retour en haut | 0.35 s (apparition) | ease | Scroll > 600 px | remontée smooth |

> **Règle d'or** : toutes les animations sont désactivées par `@media (prefers-reduced-motion: reduce)`. Aucune animation ne doit bloquer la lecture du contenu (découvrabilité immédiate).

---

## 7. ÉTATS DES COMPOSANTS

| Élément | Défaut | Hover | Focus | Active | Disabled | Loading |
|---|---|---|---|---|---|---|
| Bouton primaire | dégradé vert | -3 px, ombre forte | ring 2px vert | -1 px | opacity .5 | spinner blanc 20 px |
| Bouton outline | bordure noir | fond noir | ring 2px | scale .98 | opacity .5 | — |
| Lien menu | noir | vert + souligné | ring | — | — | — |
| Carte | ombre douce | -6/8 px, ombre verte | ring | — | — | skeleton gris |
| Input | bordure grise | — | bordure 2px verte | — | fond gris | — |
| Input erreur | — | — | bordure rouge + message | — | — | — |
| Slider dot | gris `#C9D6CE` | vert | — | vert scale 1.3 | — | — |
| Accordéon | bordure | ombre | ring | ouvert ombre | — | — |

---

## 8. RESPONSIVE & BREAKPOINTS

| Breakpoint | Largeur | Changements clés |
|---|---|---|
| **Desktop** | ≥ 1025 px | Menu horizontal, grilles 3–4 col., timeline centrée |
| **Tablette** | 641–1024 px | Burger mobile, grilles 2 col., hero-widget 1 col. |
| **Mobile** | ≤ 640 px | Sections 64 px, grilles 1 col., formulaire CTA 1 col., topbar réseaux masqués |

**Règles**
- Conteneur `92%` de largeur, max 1180 px.
- Grilles : `repeat(auto-fit, minmax(280–300px, 1fr))` pour s'adapter automatiquement.
- Images toujours `max-width:100%`.
- Cibles tactiles ≥ 44×44 px.
- Test sur 320, 375, 768, 1024, 1440 px.

---

## 9. ACCESSIBILITÉ (WCAG 2.1 AA)

- **Contraste** : toutes les combinaisons texte/fond vérifiées (voir 2.2).
- **Navigation clavier** : tous les éléments interactifs atteignables ; focus visible systématique.
- **ARIA** : `aria-label` sur les boutons icônes, `aria-expanded` sur accordéons/burger, `aria-live` sur les compteurs (polite), `role` des slides.
- **Formulaires** : `label` associés, `aria-invalid` + messages d'erreur reliés (`aria-describedby`).
- **Alternatives** : `alt` descriptifs sur toutes les images.
- **Mouvement** : `prefers-reduced-motion` respecté partout.
- **Sémantique** : une seule `h1` par page, hiérarchie h2/h3 cohérente, `header/nav/main/section/footer`.
- **Redimensionnement** : site fonctionnel jusqu'à 200 % de zoom.

---

## 10. PERFORMANCE & SEO

**Performance**
- Images : WebP, `<img loading="lazy">` (hors hero et LCP).
- Hero : `<link rel="preload" as="image">` sur l'image LCP.
- JS : une seule IIFE par page, `defer` si CDN.
- Observateurs (IntersectionObserver) plutôt que calculs au scroll pour les reveals.
- Objectif Lighthouse : ≥ 90 (Perf/A11y/Best practices/SEO).
- Éviter les animations `top/left` (utiliser `transform`).

**SEO**
- URL SEF (alias) : `/etudier-en-france`, `/nos-partenaires`, etc.
- `title` ≤ 60 car. + `meta description` ≤ 155 car. par page.
- H1 unique par page.
- Données structurées JSON-LD : `LocalBusiness` (adresse, téléphones, horaires) et `FAQPage` (questions/réponses).
- Open Graph + Twitter Cards.
- Plan de site XML + robots.txt.
- Canonical sur chaque page.

---

## 11. INTÉGRATION TECHNIQUE JOOMLA

1. **Template** : Helix Ultimate / Gantry 5 ou template custom. Les tokens `:root` et les styles globaux (header, footer, boutons, cartes) vivent dans `css/custom.css`.
2. **Articles** : un article Joomla par page ; HTML/CSS/JS scopé `.ci-` collé en mode CodeMirror. Désactiver le filtrage HTML pour l'article (sinon Joomla supprime `<style>`/`<script>`).
3. **Menus** : hiérarchie (Accueil, Visa Étude ▾, Visa Travail ▾, Campus Inter ▾, Blog) ; méga-menu via le template si supporté.
4. **Formulaires** : composant natif Contacts ou RSForm! Pro ; le formulaire CTA devient un module custom HTML `.ci-`.
5. **Médias** : import dans le Gestionnaire de médias, références relatives (`images/campusinter/...`).
6. **Header/Footer partagés** : déplacer les gabarits header/footer dans `index.php` du template (ou module position).
7. **Extensions** : SEO (sh404SEF ou natif), plan de site, sauvegardes Akeeba, cache activé.
8. **Sécurité** : HTTPS forcé, 2FA admin, restrictions de plugins.

---

## 12. CHECKLIST QUALITÉ (recette)

- [ ] Charte respectée sur 100 % des écrans (couleurs, typo, rayons)
- [ ] Contraste AA vérifié sur tous les textes
- [ ] Responsive OK 320 / 375 / 768 / 1024 / 1440
- [ ] Animations fluides (60 fps), `reduced-motion` OK
- [ ] Navigation clavier + focus visible
- [ ] Formulaires avec validation + messages français
- [ ] Toutes les images ont un `alt`
- [ ] Une seule `h1` par page
- [ ] Lazy loading + WebP
- [ ] Lighthouse ≥ 90
- [ ] Liens WhatsApp/tél/email fonctionnels
- [ ] Page Merci + 404 opérationnelles
- [ ] Recette validée par le client avant mise en ligne

---



Oui. Ton cahier actuel est déjà très complet sur le plan technique, mais il est encore fortement orienté « design system / composants ». Pour obtenir un résultat réellement exceptionnel, il faut davantage définir la direction artistique, le rôle des images, la composition des grandes sections, la profondeur visuelle, les transitions entre sections et surtout la manière dont le design raconte l'histoire de Campus Inter.

Je te propose donc une version réécrite et enrichie du cahier de design, pensée pour produire un site beaucoup plus haut de gamme, élégant et immersif, tout en restant réaliste à intégrer dans Joomla.

Cahier de design — Campus Inter Premium
0. Direction artistique générale

Campus Inter ne doit pas être présenté comme un simple site d'agence de visa ou d'orientation.

Le site doit donner l'impression d'une agence internationale moderne qui accompagne une personne dans le passage d'une ambition locale vers une opportunité internationale.

L'expérience visuelle doit raconter cette progression :

Rêve → Orientation → Préparation → Mobilité → Installation → Réussite

Le design doit donc associer quatre dimensions :

Élégance institutionnelle
Aspiration internationale
Chaleur humaine
Technologie discrète

L'objectif n'est pas de multiplier les animations, les gradients ou les effets 3D.

L'objectif est de créer une impression de qualité, de maîtrise et de confiance.

1. Concept créatif : « From Ambition to Horizon »

Le concept visuel principal du site sera :

From Ambition to Horizon

L'identité graphique doit symboliser le voyage d'un étudiant ou d'un professionnel depuis son projet initial jusqu'à son avenir international.

Métaphore visuelle

Le site utilisera subtilement :

les horizons ;
les routes ;
les lignes de trajectoire ;
les cartes ;
les passeports ;
les campus ;
les avions ;
les bâtiments européens ;
les silhouettes urbaines ;
les fenêtres ;
les perspectives architecturales ;
les cartes géographiques abstraites.

Ces éléments ne doivent jamais être utilisés de manière littérale ou excessive.

Ils doivent apparaître comme des détails graphiques raffinés.

2. Identité visuelle
2.1 Couleurs principales

Les couleurs officielles restent inchangées.

:root {
    --ci-vert: #159447;
    --ci-vert-fonce: #0F7A3A;
    --ci-vert-clair: #1DB85E;

    --ci-noir: #252525;

    --ci-blanc: #FFFFFF;

    --ci-fond: #F4F7F4;
    --ci-fond-2: #F8FAF8;

    --ci-texte: #2F3B36;
    --ci-texte-secondaire: #6B7280;

    --ci-bordure: #E7EFE8;

    --ci-whatsapp: #25D366;
}

Mais leur utilisation doit être beaucoup plus sophistiquée.

Répartition visuelle
65 % blanc / tons très clairs
20 % anthracite
10 % vert
5 % éléments graphiques et accents

Le vert ne doit jamais devenir la couleur dominante de toutes les sections.

Il doit être utilisé comme une signature visuelle.

3. Philosophie des images

Les images doivent devenir un élément essentiel de l'expérience.

Le site ne doit pas être composé uniquement de :

texte + carte + bouton + texte + carte.

Les images doivent servir à raconter l'histoire de Campus Inter.

3.1 Type d'images

Privilégier des photographies :

réalistes ;
lumineuses ;
haut de gamme ;
naturelles ;
humaines ;
contemporaines ;
prises dans des environnements occidentaux crédibles.

Éviter les images de banques d'images trop artificielles.

Les personnes

Les étudiants doivent paraître :

naturels ;
confiants ;
ambitieux ;
heureux mais pas artificiellement souriants.

Privilégier :

étudiants africains ;
jeunes professionnels africains ;
étudiants dans des campus européens ;
personnes dans des gares/aéroports ;
étudiants travaillant dans des bibliothèques ;
scènes de vie quotidienne.
4. Système d'imagerie

Chaque grande page doit avoir 3 à 6 images principales, pas nécessairement dans chaque section.

Les images doivent alterner entre :

Image immersive

Grande photographie occupant une partie importante de l'écran.

Image éditoriale

Image associée à un bloc de texte.

Image contextuelle

Petite image intégrée dans une carte ou un élément graphique.

Image pleine largeur

Utilisée pour créer une rupture visuelle.

Image flottante

Image positionnée avec une légère superposition sur une autre section.

5. Hero principal — expérience premium

Le hero doit être la section la plus impressionnante du site.

Structure :

┌───────────────────────────────────────────────┐
│ NAVIGATION                                    │
├───────────────────────────────────────────────┤
│                                               │
│   VOTRE AVENIR COMMENCE ICI                   │
│                                               │
│   Étudier. Travailler.                        │
│   Voyager. Réussir.                           │
│                                               │
│   Campus Inter vous accompagne                │
│   vers votre projet international.            │
│                                               │
│   [ Commencer mon projet ]                    │
│                                               │
│                         ┌──────────────┐      │
│                         │              │      │
│                         │   PHOTO      │      │
│                         │   ÉTUDIANT   │      │
│                         │              │      │
│                         └──────────────┘      │
│                                               │
└───────────────────────────────────────────────┘
Direction artistique

Utiliser une photographie verticale d'un étudiant dans un environnement universitaire européen.

L'image doit être intégrée dans une composition asymétrique.

Elle ne doit pas simplement être utilisée comme :

background-image

mais comme un véritable élément graphique.

Effet visuel
image légèrement arrondie ;
profondeur ;
léger déplacement au scroll ;
élément graphique vert derrière l'image ;
petite carte flottante ;
ligne de trajectoire très discrète.
6. Hero : éléments flottants

Autour de l'image principale :

Carte 01
+2500
Étudiants accompagnés
Carte 02
98%
Satisfaction
Carte 03
France • Belgique • Europe

Ces éléments doivent être très sobres.

Pas de grosses cartes flottantes multicolores.

Utiliser :

blanc ;
ombre très douce ;
typographie élégante ;
petit accent vert.
7. Section « Votre projet commence ici »

Après le hero, créer une section éditoriale.

Fond blanc.

À gauche :

NOTRE MISSION

Votre projet international
mérite un accompagnement
à la hauteur de vos ambitions.

À droite :

une photographie d'un étudiant consultant un conseiller Campus Inter.

Composition

L'image occupe environ 50 % de la section.

Le texte occupe 40 %.

Le reste est constitué d'espace négatif.

L'espace vide est volontaire.

8. Section « Pourquoi Campus Inter »

Cette section doit être beaucoup plus visuelle qu'une simple grille de quatre cartes.

Créer une composition asymétrique.

              [ IMAGE ]
                   │
       ┌───────────┴───────────┐
       │                       │
   Expertise               Transparence
       │                       │
       └───────────┬───────────┘
                   │
               Réussite

Chaque avantage possède :

numéro ;
petite icône ;
titre ;
texte court.

Les numéros :

01
02
03
04

sont affichés en très grande taille, avec une opacité faible.

9. Section « Nos domaines d'expertise »

Cette section doit introduire les services.

Utiliser 6 cartes visuelles, mais éviter les cartes classiques.

Chaque carte doit contenir une image.

Exemple

Étudier en France

Image :

étudiant devant une université française.

En bas :

01

ÉTUDIER EN FRANCE

Un accompagnement complet
pour construire votre projet.

Découvrir →

Au hover :

zoom image ;
déplacement léger ;
apparition du texte ;
accent vert.
10. Grande section immersive « Étudier en France »

Créer une section presque pleine largeur.

Image panoramique :

Paris / campus universitaire / étudiant africain

Overlay sombre très léger.

Au centre :

ÉTUDIER EN FRANCE

Transformez votre projet
en réalité.

[ Découvrir notre accompagnement ]

La section doit avoir une hauteur d'environ :

650–750 px desktop

Sur mobile :

520–600 px

11. Section « Comment ça marche ? »

Cette section doit raconter le parcours.

Utiliser une timeline horizontale desktop.

01 ───────── 02 ───────── 03 ───────── 04

ORIENTATION    DOSSIER       VISA       DÉPART

Chaque étape peut être accompagnée d'une petite photographie circulaire ou carrée.

Au scroll :

la ligne se remplit progressivement de vert ;
les étapes apparaissent ;
les images se révèlent.

Sur mobile :

la timeline devient verticale.

12. Section « Une équipe qui vous accompagne »

Cette section doit humaniser Campus Inter.

Utiliser une grande photographie d'équipe.

La photographie doit être authentique.

À côté :

UNE ÉQUIPE À VOS CÔTÉS

Nous ne nous contentons pas
de traiter votre dossier.

Nous vous accompagnons
à chaque étape de votre projet.

Ajouter :

→ Écoute
→ Expertise
→ Disponibilité
→ Transparence
13. Section partenaires

Le fond devient anthracite.

Mais au lieu d'une simple liste de logos :

Créer une expérience de réseau international.

Composition
                 NOS PARTENAIRES

      ──────────────────────────────

        LOGO       LOGO       LOGO

               LOGO

      LOGO                   LOGO

        France • Belgique • Europe

Les logos sont monochromes blancs.

Un marquee horizontal très lent peut être utilisé.

14. Section statistiques

Créer une section noire minimaliste.

Exemple :

+2500
ÉTUDIANTS ACCOMPAGNÉS


+50
PARTENAIRES


10+
ANNÉES D'EXPÉRIENCE


98%
SATISFACTION

Les nombres doivent être gigantesques.

Le vert est uniquement utilisé sur :

+
%
15. Témoignages

Éviter le classique :

[photo]
« Très bonne agence... »

Créer plutôt des témoignages éditoriaux.

Grande photo à gauche.

À droite :

★★★★★

« Campus Inter m'a accompagné
depuis le choix de ma formation
jusqu'à mon arrivée en France. »

— Nom Prénom
Étudiant en Master
France

Ajouter éventuellement :

France
Université XXX
Promotion 2026

Cela rend le témoignage beaucoup plus crédible.

16. Section « Votre prochaine destination »

Créer une grande section avec une photographie aérienne ou architecturale.

Exemple :

Paris.

La section peut afficher :

VOTRE PROCHAINE DESTINATION

France
Belgique
Europe

Votre projet commence
aujourd'hui.

[ Construire mon projet ]

Cette section constitue une transition émotionnelle vers le CTA final.

17. CTA final

Le CTA final doit être très élégant.

Fond :

linear-gradient(
    135deg,
    #252525,
    #159447
)

Ajouter une image très discrète en arrière-plan :

avion ;
étudiant ;
skyline européenne ;
campus.

Texte :

PRÊT À COMMENCER ?

Votre projet international
commence par une première étape.

[ Démarrer mon projet ]
18. Page « Étudier en France »

Cette page doit avoir sa propre identité photographique.

Hero :

Grande image d'un étudiant africain dans un campus français.

Puis :

Pourquoi la France ?

Utiliser 5 blocs éditoriaux avec images.

Exemple :

01
QUALITÉ ACADÉMIQUE

[ image ]
02
DIVERSITÉ DES FORMATIONS

[ image ]
19. Page « Travailler en France »

Utiliser davantage d'images professionnelles.

Photographies :

ingénieur ;
professionnel de santé ;
technicien ;
restauration ;
industrie.

Chaque secteur devient une mini scène photographique.

20. Page « Visa & démarches »

Ici, le design doit devenir plus institutionnel.

Utiliser :

passeport ;
documents ;
ordinateur ;
rendez-vous ;
conseiller.

Éviter les effets excessifs.

Le visiteur doit ressentir :

ordre + sécurité + précision.

21. Page « Installation »

Utiliser des photographies représentant la vie après l'arrivée :

logement ;
transport ;
banque ;
campus ;
ville ;
vie quotidienne.

Le message visuel doit être :

« Nous ne vous accompagnons pas uniquement jusqu'au visa. »

22. Page « Nos partenaires »

Cette page doit être très premium.

Pour chaque partenaire :

┌─────────────────────────────┐
│                             │
│          PHOTO              │
│                             │
├─────────────────────────────┤
│ LOGO                        │
│                             │
│ Nom du partenaire           │
│ Description                 │
│                             │
│ + chiffres clés             │
│                             │
│ Découvrir →                 │
└─────────────────────────────┘

Alterner les compositions :

IMAGE | TEXTE
TEXTE | IMAGE
IMAGE | TEXTE
23. Blog

Le blog doit ressembler à un magazine éditorial international, pas à une simple liste d'articles.

Utiliser :

grandes images ;
titres forts ;
catégories ;
dates ;
temps de lecture.

Le premier article est présenté en grand :

┌──────────────────────────────────────────────┐
│                                              │
│                 GRANDE IMAGE                 │
│                                              │
├──────────────────────┬───────────────────────┤
│ VISA                 │                       │
│                      │ Comment préparer      │
│                      │ son projet d'études   │
│                      │ en France ?           │
└──────────────────────┴───────────────────────┘
24. Navigation

Le header doit être extrêmement propre.

Desktop
LOGO

Étudier
Travailler
Visa
Installation
Écoles
À propos

              [ Mon projet ]

Le bouton « Mon projet » est le CTA principal.

Éviter d'avoir 4 boutons différents dans le header.

25. Micro-interactions

Les animations doivent être subtiles.

Priorité :

opacity ;
transform ;
scale ;
clip-path ;
parallax léger.

Éviter :

animations permanentes ;
rotations excessives ;
rebonds ;
effets flashy ;
particules trop nombreuses.

Le mouvement doit donner l'impression que le site est vivant, pas qu'il essaie d'impressionner.

26. Effet « Editorial Premium »

Introduire certaines compositions inspirées des sites éditoriaux haut de gamme.

Par exemple :

01
──────────────

NOTRE VISION

Accompagner une génération
qui regarde au-delà des frontières.

                           [IMAGE]

Les grands numéros peuvent être utilisés comme éléments graphiques.

27. Utilisation du vide

Une règle importante :

Ne pas remplir tous les espaces.

L'espace blanc fait partie du design.

Certaines sections doivent avoir :

padding-top: 140px;
padding-bottom: 140px;

plutôt que 60 ou 70 px.

Cela permettra de donner au site une sensation beaucoup plus luxueuse.

28. Système d'images technique

Toutes les images doivent être optimisées.

<picture>
    <source
        srcset="images/campus-inter/hero.webp"
        type="image/webp"
    >

    <img
        src="images/campus-inter/hero.jpg"
        alt="Étudiant africain dans un campus universitaire européen"
        loading="eager"
    >
</picture>

Pour les images secondaires :

<img
    src="images/campus-inter/france.webp"
    alt="Étudiant devant une université en France"
    loading="lazy"
>
29. Système de profondeur

La profondeur doit venir de :

ombres ;
superpositions ;
images ;
transparences ;
gradients ;
espaces ;
couches.

Pas uniquement des cartes.

Exemple :

BACKGROUND
     ↓
SHAPE VERTE
     ↓
IMAGE
     ↓
CARD
     ↓
TEXT

Cette approche donnera beaucoup plus de richesse visuelle.

30. Responsive

Sur mobile, le design ne doit pas simplement devenir :

desktop réduit.

Il doit être recomposé.

Par exemple :

Desktop :

IMAGE              TEXTE
       IMAGE
TEXTE              IMAGE

Mobile :

IMAGE

TEXTE

IMAGE

TEXTE

Les grandes images doivent conserver leur impact.

31. Animations au scroll

Utiliser principalement :

transform: translateY();
opacity: ;
scale();
clip-path:;

Exemple :

Section invisible
       ↓
15 % visible
       ↓
Fade + translateY
       ↓
Position finale

Durée :

600–800 ms

Easing :

cubic-bezier(.22,1,.36,1)
32. Performance

Malgré le caractère visuel premium :

WebP/AVIF ;
images responsives ;
lazy loading ;
preload uniquement du hero ;
animations GPU ;
IntersectionObserver ;
JS différé ;
pas de librairies inutiles.

Le site doit rester rapide.

33. Architecture CSS

Toutes les classes doivent être préfixées :

.ci-

Exemples :

.ci-hero
.ci-hero__content
.ci-hero__media

.ci-story
.ci-story__image
.ci-story__content

.ci-services
.ci-service-card

.ci-testimonials
.ci-testimonial

.ci-cta
.ci-footer

Cela évite les conflits avec Joomla et Helix Ultimate.

34. Règle absolue concernant les images

Une image ne doit jamais être ajoutée uniquement parce qu'il reste un espace vide.

Chaque image doit répondre à une question :

« Qu'est-ce que cette image raconte sur Campus Inter ? »

Si elle ne raconte rien, elle doit être supprimée.

35. Expérience finale recherchée

Lorsque quelqu'un arrive sur le site, il doit ressentir progressivement :

Première impression

« C'est une agence sérieuse. »

Après quelques secondes

« Ils comprennent réellement les projets internationaux. »

Après quelques sections

« Ils accompagnent les étudiants de manière complète. »

Après les témoignages

« Des personnes leur ont réellement fait confiance. »

À la fin

« Je peux leur confier mon projet. »

Et finalement :

« Je veux commencer mon projet avec Campus Inter. »

36. Règle artistique finale

Le site doit éviter l'apparence :

template Joomla + cartes + boutons + texte.

Il doit plutôt ressembler à :

agence internationale premium + magazine éditorial + plateforme d'accompagnement.

L'identité de Campus Inter doit être immédiatement reconnaissable grâce à la combinaison :

Vert Campus Inter + photographie humaine + espace blanc + anthracite + compositions éditoriales + trajectoires internationales + typographie élégante + animations discrètes.

Le résultat recherché est un site élégant, lumineux, humain, international et technologiquement moderne, où chaque section possède sa propre composition tout en restant parfaitement cohérente avec l'identité globale de Campus Inter.

*Fin de la spécification UX/UI. Ce document fait référence pour toute maquette ou intégration future du site Campus Inter.*
