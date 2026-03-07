# 🐒 MonkeyStats - Projet SAE 203

**Création de Site Web avec Données Publiques et Application du Modèle MVC**

Ce projet a été réalisé dans le cadre de la **SAE 203** (département MMI). Il s'agit d'une application web développée en **PHP natif** respectant strictement l'architecture **MVC (Modèle-Vue-Contrôleur)**. Elle exploite l'API de [Monkeytype](https://monkeytype.com) pour afficher et sauvegarder des statistiques de frappe au clavier.

---

## 🌟 Fonctionnalités

- **Recherche de Profils :** Consultez les statistiques détaillées (Tests complétés, Temps de frappe, XP, Niveau calculé dynamiquement) de n'importe quel joueur.
- **Records Personnels (PB) :** Affichage des meilleurs scores (WPM et Précision) pour les modes 15s et 60s.
- **Leaderboard Global :** Naviguez à travers le top 50 mondial directement depuis le site (filtrage par mode de temps).
- **Système de Cache SQL (Exigence SAE) :** Afin de respecter les consignes d'utilisation des données et d'optimiser les performances, chaque recherche déclenche une sauvegarde immédiate dans une base de données MySQL. Si le profil est recherché à nouveau dans les 10 minutes, les données sont lues localement via la BDD sans solliciter l'API externe.

---

## 🛠️ Technologies & Architecture

- **Backend :** PHP (Routeur frontal `index.php`).
- **Base de données :** MySQL / MariaDB.
- **Sécurité :** Utilisation exclusive de **PDO** avec requêtes préparées (`prepare()`, `execute()`) pour bloquer les injections SQL.
- **Frontend :** HTML5 & CSS3 natif.
- **Modèle de conception :** MVC complet.

### Arborescence

```text
/projet
├── config/
│   └── database.php             # Identifiants PDO et clé API (ApeKey)
├── controller/
│   ├── LeaderboardController.php
│   └── UserController.php       # Fait le lien entre la saisie utilisateur et les modèles
├── css/
│   ├── home.css
│   ├── leaderboard.css
│   └── user.css                 # Feuilles de style séparées
├── model/
│   ├── LeaderboardModel.php
│   └── UserModel.php            # Requêtes API (cURL) et requêtes SQL (INSERT/UPDATE/SELECT)
├── view/
│   ├── homeView.php
│   ├── leaderboardView.php
│   └── userStatsView.php        # Affichage pur (aucun traitement de données)
├── database.sql                 # Script de création des tables
└── index.php                    # Point d'entrée unique (Routeur)
```

---

## 🚀 Installation & Déploiement

### Prérequis
- Un serveur web (Apache / Nginx / PHP Built-in server)
- PHP 8.0+ avec l'extension `pdo_mysql`
- MySQL ou MariaDB

### Étapes

1. **Cloner le dépôt :**
   ```bash
   git clone https://github.com/snowflakid/monkeystats.git
   cd monkeystats
   ```

2. **Configurer la base de données :**
   Connectez-vous à MySQL et exécutez le script SQL fourni pour créer la base et les tables :
   ```bash
   mysql -u root -p < database.sql
   ```
   
3. **Configurer les identifiants :**
   Éditez le fichier `config/database.php` pour y insérer vos identifiants SQL locaux et votre `ApeKey` Monkeytype.
   ```php
   $host = 'localhost';
   $db = 'sae203_db';
   $user = 'votre_utilisateur';
   $pass = 'votre_mot_de_passe';
   define('MONKEYTYPE_APE_KEY', 'votre_cle_api_ici');
   ```

4. **Lancer l'application :**
   En développement local, vous pouvez utiliser le serveur interne de PHP :
   ```bash
   php -S localhost:8000
   ```
   Rendez-vous ensuite sur `http://localhost:8000`.

---
*Projet réalisé dans le cadre universitaire.*
