# Documentation technique — MonkeyStats (SAÉ 2.03)

## Architecture de l'application

MonkeyStats est une application web PHP organisée en **MVC** (Modèle-Vue-Contrôleur) sans framework. Le point d'entrée est `index.php`, qui lit le paramètre `?action` et instancie le contrôleur correspondant.

```
Requête HTTP
    └─ index.php (routeur)
         ├─ UserController → UserModel → API Monkeytype / MariaDB → userStatsView.php
         ├─ UserController::home() → LeaderboardModel → homeView.php
         └─ LeaderboardController → LeaderboardModel → API / MariaDB → leaderboardView.php
```

**Pourquoi MVC sans framework ?** Pour un projet de cette taille, un framework comme Laravel aurait apporté plus de complexité que de bénéfice. Le pattern MVC manuel permet de garder le contrôle total sur chaque couche et de bien comprendre le flux de données.

---

## Choix techniques

### PHP + Nginx + PHP-FPM

Nginx est utilisé comme serveur web avec **PHP-FPM** (FastCGI Process Manager) qui gère l'exécution des scripts PHP dans un processus séparé. Nginx est plus léger qu'Apache pour servir des fichiers statiques et gère mieux la concurrence, ce qui le rend plus adapté à un VPS avec peu de RAM. PHP-FPM communique avec Nginx via un socket Unix (`/run/php/php8.1-fpm.sock`), ce qui est plus performant qu'un socket TCP.

### MariaDB + PDO

MariaDB est utilisé comme base de données. Toutes les requêtes passent par **PDO avec requêtes préparées**, ce qui élimine tout risque d'injection SQL. Le charset `utf8mb4` est imposé pour supporter tous les caractères Unicode (pseudos avec caractères spéciaux).

### Système de cache

Pour éviter de surcharger l'API Monkeytype, les profils sont mis en cache en base de données pendant **10 minutes** (colonne `last_updated`). Si les données ont moins de 10 minutes, on les retourne directement depuis MariaDB. Au-delà, on refait un appel API et on met à jour la base.

```
getUserStats($username)
    ├─ SELECT en BDD
    ├─ Si absent ou last_updated > 10 min → appel API → INSERT/UPDATE
    └─ Retourner les données
```

### Sécurité

- **Pas d'injection SQL** : PDO + requêtes préparées sur toutes les requêtes
- **Pas de XSS** : `htmlspecialchars()` sur toutes les variables affichées dans les vues
- **Clé API hors dépôt** : `config/database.php` est gitignorée, la clé n'est jamais versionnée
- **HTTPS** : certificat Let's Encrypt via Certbot, redirection automatique HTTP → HTTPS

### Monitoring

**Netdata** surveille en temps réel les métriques serveur (CPU, RAM, requêtes réseau). Il est exposé via un reverse proxy Apache sur `/monitoring/`, protégé par une authentification HTTP Basic (`.htpasswd`). Le port 19999 est fermé au niveau du pare-feu Azure.

---

## Configuration du serveur

- **OS** : Ubuntu 22.04 LTS sur VM Azure (B1s)
- **Serveur web** : Nginx 1.18 + PHP-FPM 8.1
- **PHP** : 8.1 avec `pdo_mysql`
- **Base de données** : MariaDB 10.6
- **Certificat TLS** : Let's Encrypt via Certbot (renouvellement automatique)
- **Domaine** : DuckDNS (sous-domaine gratuit mis à jour toutes les 5 minutes par cron)

---

## Utilisation des LLM et de l'IA dans le projet

Nous avons utilisé **Claude** (Anthropic) comme assistant pendant le développement. Il est important d'être précis sur ce que ça a apporté — et ce que ça n't a pas remplacé.

### Ce que l'IA a fait concrètement

- **Débogage** : quand PHP renvoyait `could not find driver` pour PDO, Claude a identifié que l'extension `pdo_mysql` n'était pas activée dans `php.ini` et a donné la ligne exacte à décommenter. Sans ça, on aurait cherché beaucoup plus longtemps.
- **Structure HTML/accessibilité** : Claude a proposé d'ajouter `aria-labelledby` sur les sections, `scope="col"` sur les `<th>`, et le lien d'évitement `.skip-link`. On ne connaissait pas tous ces attributs en détail.
- **Formulation** : aide pour rédiger des commentaires et ce rapport.

### Ce que l'IA n'a pas fait

- La logique du **cache BDD** (savoir quand re-fetcher l'API) a été réfléchie et écrite manuellement.
- Le **schéma SQL**, les **requêtes préparées PDO**, et le routage MVC ont été écrits sans génération automatique.
- Les **choix d'architecture** (MVC sans framework, PDO plutôt que mysqli, MariaDB) sont des décisions que nous avons prises après réflexion.

### Notre utilisation était cadrée

On n'a pas demandé à l'IA de "faire le projet". On lui posait des questions précises sur des problèmes précis : "pourquoi cette erreur PHP ?", "quelle balise pour ce bloc ?", "comment protéger Netdata avec Apache ?". La différence entre utiliser un LLM comme un moteur de recherche avancé et lui déléguer la réflexion, c'est que dans le deuxième cas on ne comprend pas ce qu'on rend.

Toutes les décisions techniques restent les nôtres — l'IA a accéléré certaines recherches, pas remplacé la compréhension.
