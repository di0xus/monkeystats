# Documentation technique — MonkeyStats

## Architecture de l'application

MonkeyStats est une application web PHP organisée en **MVC** (Modèle-Vue-Contrôleur).

```
Requête HTTP
    └─ index.php (routeur)
         ├─ UserController → UserModel → API Monkeytype / MariaDB → userStatsView.php
         ├─ UserController::home() → LeaderboardModel → homeView.php
         └─ LeaderboardController → LeaderboardModel → API / MariaDB → leaderboardView.php
```

---

## Choix techniques

Nginx est utilisé comme serveur web, deployé sur le VPS Azure, avec DuckDNS et le certificat de Certbot.

MariaDB est utilisé comme base de données. Toutes les requêtes passent par **PDO avec requêtes préparées**, ce qui élimine tout risque d'injection SQL. On a utiliser le charset `utf8mb4` pour supporter tout les pseudos avec caractères spéciaux.

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
- **Clé API hors dépôt** : `config/database.php` est gitignorée, la clé n'est jamais versionnée, nous avons utilisé claude code pour push le code car on avait plusieurs problemes de déploiement.
- **HTTPS** : certificat via Certbot

### Monitoring

**Netdata** surveille en temps réel les métriques serveur (CPU, RAM, requêtes réseau). Il est exposé via un reverse proxy Apache sur `/monitoring/`, protégé par une authentification HTTP Basic (`.htpasswd`).

---

## Configuration du serveur

- **OS** : Ubuntu 
- **Serveur web** : Nginx + PHP-FPM
- **PHP** : 8.3 avec `pdo_mysql`
- **Base de données** : MariaDB 
- **Certificat TLS** : Let's Encrypt via Certbot
- **Domaine** : DuckDNS

---

## Utilisation des LLM et de l'IA dans le projet

Nous avons utilisé **Claude Code** comme assistant pendant le développement. Au-delà des exemples ci-dessous, Claude a aussi aidé sur plusieurs petits problèmes ponctuels tout au long du projet (syntaxe PHP, config serveur, questions rapides).

### Ce que l'IA a fait concrètement

- **Débogage** : quand PHP renvoyait `could not find driver` pour PDO, Claude a identifié que l'extension `pdo_mysql` n'était pas activée dans `php.ini` et a donné la ligne exacte à décommenter. Sans ça, on aurait cherché beaucoup plus longtemps.
- **Erreur 471 après déploiement** : après avoir hébergé l'application sur le VPS Azure, l'API Monkeytype renvoyait un code 471 (code non standard signifiant "ApeKey manquante ou invalide"). Claude a d'abord orienté vers une vérification du fichier `config/database.php` sur le serveur, puis vers les permissions du scope de la clé. En testant l'appel API directement avec `curl` depuis le VPS, on a constaté que la clé avait été **désactivée côté Monkeytype**. La solution était juste de cocher la case d'activation...
- **Structure HTML/accessibilité** : Claude a proposé d'ajouter `aria-labelledby` sur les sections, `scope="col"` sur les `<th>`, et le lien d'évitement `.skip-link`. On ne connaissait pas tous ces attributs en détail.

### Notre utilisation était cadrée

On lui posait des questions précises sur des problèmes précis : "pourquoi cette erreur PHP ?", "quelle balise pour ce bloc ?", la différence entre utiliser un LLM comme un moteur de recherche avancé et lui déléguer la réflexion, c'est que dans le deuxième cas on ne comprend pas ce qu'on rend.

Toutes les décisions techniques restent les nôtres, l'IA a accéléré certaines recherches, pas remplacé la compréhension.
