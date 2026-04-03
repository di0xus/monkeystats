# Rapport d'intégration — MonkeyStats (SAÉ 2.03)

## Choix de structure HTML

J'ai organisé chaque page autour des balises sémantiques HTML5 pour que la structure reflète le sens du contenu, pas juste la mise en page.

Sur toutes les pages : un `<header>` pour l'en-tête, un `<main>` pour le contenu principal (avec `id="contenu"` pour le lien d'évitement), et un `<footer>` pour la navigation secondaire. Les zones de navigation utilisent toutes `<nav>` avec un `aria-label` pour les distinguer entre elles.

Sur la page de profil, j'ai découpé les stats en deux `<section>` avec `aria-labelledby` relié au `<h2>` correspondant — une pour les stats générales, une pour les meilleurs scores. La liste des statistiques est un `<ul>` / `<li>` parce que c'est effectivement une liste de données, pas une simple suite de `<div>`.

Pour le classement, j'ai utilisé un `<table>` complet : `<thead>` / `<tbody>`, `scope="col"` sur chaque `<th>`, et une `<caption>` qui décrit le tableau (top 50, mode, langue). C'est la balise la plus adaptée pour afficher des données tabulaires avec rang, pseudo, wpm et précision.

Le formulaire de recherche a un `<label>` associé au champ via `for="pseudo"` — il est visuellement masqué (classe `.visually-hidden`) mais bien présent dans l'arbre d'accessibilité.

---

## Stratégie CSS

**Variables CSS** : toutes les couleurs et le rayon de bordure sont définis dans `:root` (ex. `--bg`, `--accent`, `--muted`, `--radius`). Le thème reprend les couleurs de Monkeytype. Modifier une couleur à un seul endroit suffit à l'appliquer partout.

**Flexbox** est utilisé pour les layouts unidimensionnels : centrage de la page d'accueil (`flex-direction: column`, `justify-content: center`), en-tête de profil (avatar + infos côte à côte), navigation.

**Grid** est utilisé pour les layouts bidimensionnels : la grille des stats (`repeat(auto-fit, minmax(150px, 1fr))`) et la grille des meilleurs scores (2 colonnes fixes `1fr 1fr`).

**Breakpoints** : deux breakpoints principaux par page.
- `600px` : le header de profil passe en colonne (avatar au-dessus du nom), la grille des PB passe sur une colonne, les paddings du tableau sont réduits.
- `380-400px` : les stats passent sur une seule colonne, la dernière colonne du tableau (précision) est masquée pour éviter le débordement.

Pas de reset externe, juste `box-sizing: border-box` sur tout.

---

## Accessibilité

**Lien d'évitement** : chaque page commence par `<a href="#contenu" class="skip-link">Aller au contenu</a>`. Il est invisible par défaut et apparaît au focus clavier (`top: 0`), ce qui permet aux utilisateurs au clavier de sauter la navigation répétitive.

**Navigation clavier** : tous les éléments interactifs ont un style `focus-visible` visible (outline jaune 2px). Le nom d'utilisateur est cliquable pour copier le pseudo — il a `tabindex="0"` et `role="button"` pour être atteignable au clavier, et répond à Entrée et Espace.

**ARIA** : `aria-current="page"` sur le lien de temps actif dans le classement, `aria-label` sur chaque `<nav>` pour les différencier, `aria-labelledby` sur les sections, `aria-hidden="true"` sur l'avatar (purement décoratif).

**Vérification** : j'ai testé avec l'inspecteur d'accessibilité de Firefox (arbre d'accessibilité, rôles, labels) et navigué à la souris et au clavier pour vérifier la cohérence. Le validateur W3C a été utilisé pour contrôler le HTML.

---

## Répartition

**Dorian** — structure HTML des trois pages (balisage sémantique, accessibilité, formulaire), CSS complet (variables, responsive, focus styles), JavaScript (raccourci clavier, copie du pseudo).

**Matteo** — maquettage et choix du design (couleurs, typographie, mise en page générale), aide sur le CSS de la page classement.

**Adam** — rédaction du contenu textuel, tests sur différents appareils et navigateurs, vérification de la conformité HTML avec le validateur W3C.
