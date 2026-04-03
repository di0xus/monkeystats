# Guide de déploiement — MonkeyStats (VM Azure + Nginx)

> Objectif : 20/20 → VPS Azure + DuckDNS + HTTPS + PHP + MariaDB + Netdata + .htpasswd

---

## 0. Prérequis Azure

1. Créer une VM Ubuntu 22.04 LTS sur Azure (taille B1s suffit)
2. Dans **Networking** → ouvrir les ports : **22** (SSH), **80** (HTTP), **443** (HTTPS)
3. Noter l'IP publique (ex. `20.x.x.x`)

```bash
ssh azureuser@<IP>
```

---

## 1. Installation des paquets

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx php8.1-fpm php8.1-mysql php8.1-curl mariadb-server certbot python3-certbot-nginx git apache2-utils
```

---

## 2. MariaDB (2.2 : 3 pts)

```bash
sudo mysql_secure_installation
# Y Y Y Y Y
```

```bash
sudo mariadb
```
```sql
CREATE DATABASE sae203_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'sae'@'localhost' IDENTIFIED BY 'motdepasse_fort';
GRANT ALL PRIVILEGES ON sae203_db.* TO 'sae'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## 3. Déployer le projet (2.1 : 3 pts)

```bash
sudo git clone https://github.com/di0xus/monkeystats.git /var/www/monkeystats
sudo chown -R www-data:www-data /var/www/monkeystats
```

Créer la config :
```bash
sudo nano /var/www/monkeystats/config/database.php
```
```php
<?php
define('MONKEYTYPE_APE_KEY', 'ta_cle_ici');

$dsn = 'mysql:host=localhost;dbname=sae203_db;charset=utf8mb4';
return new PDO($dsn, 'sae', 'motdepasse_fort', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
```

Importer le schéma :
```bash
sudo mariadb sae203_db < /var/www/monkeystats/database.sql
```

---

## 4. Nginx — configuration principale (1.1 : 6 pts)

```bash
sudo nano /etc/nginx/sites-available/monkeystats
```
```nginx
server {
    listen 80;
    server_name monkeystats.duckdns.org;

    root /var/www/monkeystats;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.1-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }

    # Monitoring Netdata (section 7)
    location /monitoring/ {
        proxy_pass http://localhost:19999/;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;

        auth_basic "Monitoring";
        auth_basic_user_file /etc/nginx/.htpasswd;
    }
}
```

```bash
sudo ln -s /etc/nginx/sites-available/monkeystats /etc/nginx/sites-enabled/
sudo rm /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx
```

Vérifier : `http://<IP>` charge le site → ✅ 6 pts

---

## 5. DuckDNS — nom de domaine (1.2 : 1 pt)

1. Aller sur [duckdns.org](https://www.duckdns.org), créer un compte
2. Créer un sous-domaine (ex. `monkeystats`) → pointer vers l'IP Azure

Mise à jour automatique via cron :
```bash
mkdir -p ~/duckdns
nano ~/duckdns/duck.sh
```
```bash
#!/bin/bash
echo url="https://www.duckdns.org/update?domains=monkeystats&token=TON_TOKEN&ip=" | curl -k -o ~/duckdns/duck.log -K -
```
```bash
chmod +x ~/duckdns/duck.sh
crontab -e
# Ajouter :
*/5 * * * * ~/duckdns/duck.sh >/dev/null 2>&1
```

Vérifier : `http://monkeystats.duckdns.org` → ✅ 1 pt

---

## 6. HTTPS avec Certbot (1.3 : 1 pt)

```bash
sudo certbot --nginx -d monkeystats.duckdns.org
# Choisir "Redirect" pour forcer HTTPS
```

Certbot modifie automatiquement la config Nginx. Vérifier le renouvellement :
```bash
sudo certbot renew --dry-run
```

Vérifier : `https://monkeystats.duckdns.org` avec cadenas → ✅ 1 pt

---

## 7. Netdata — monitoring (3.1 : 1 pt)

```bash
bash <(curl -Ss https://my-netdata.io/kickstart.sh) --non-interactive
```

Netdata tourne sur `localhost:19999`. Le bloc `location /monitoring/` dans la config Nginx (section 4) le proxifie déjà sur `https://monkeystats.duckdns.org/monitoring/`.

Vérifier que Netdata tourne :
```bash
sudo systemctl status netdata
```

Vérifier : `https://monkeystats.duckdns.org/monitoring/` → affiche Netdata → ✅ 1 pt

---

## 8. Authentification .htpasswd (3.2 : 1 pt)

```bash
sudo htpasswd -c /etc/nginx/.htpasswd admin
# Entrer un mot de passe
sudo systemctl reload nginx
```

Le bloc `auth_basic` dans la config Nginx (section 4) protège déjà `/monitoring/`.

Vérifier : accéder à `/monitoring/` → fenêtre de login → ✅ 1 pt

---

## Récapitulatif des points

| Critère | Points | Validation |
|---|---|---|
| VPS Azure fonctionnel | 6 | `http://<IP>` charge le site |
| DuckDNS | 1 | `http://monkeystats.duckdns.org` |
| HTTPS Certbot | 1 | `https://` avec cadenas |
| PHP fonctionnel | 3 | Recherche d'un joueur fonctionne |
| MariaDB connectée | 3 | Données affichées, cache actif |
| Netdata accessible | 1 | `/monitoring/` sur le domaine |
| .htpasswd | 1 | Login demandé sur `/monitoring/` |
| Documentation | 4 | Voir `DOCUMENTATION.md` |
| **Total** | **20** | |
