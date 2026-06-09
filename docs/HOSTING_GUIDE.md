# 🚀 Guide d'Hébergement - PharmaStock Pro

Ce guide vous explique comment héberger votre application PharmaStock Pro gratuitement (ou à faible coût) sur les plateformes les plus populaires pour les développeurs Laravel.

## 🏁 Pré-requis

1.  Un compte [GitHub](https://github.com).
2.  Votre code poussé (push) sur un dépôt GitHub.
3.  Un compte sur la plateforme de votre choix (Railway ou Render).

---

## 🚂 Option 1 : Railway (Recommandé)
Railway est extrêmement simple et gère parfaitement Laravel.

### Étapes :
1.  **Nouveau Projet** : Allez sur [Railway.app](https://railway.app), cliquez sur "New Project" -> "Deploy from GitHub repo".
2.  **Base de Données** : 
    - Cliquez sur "Add Service" -> "Database" -> "Add MySQL" (ou PostgreSQL).
    - Une fois créée, allez dans l'onglet "Variables" de votre base de données pour récupérer les identifiants.
3.  **Variables d'Environnement** :
    - Cliquez sur votre service applicatif (votre repo).
    - Allez dans l'onglet **Variables** et ajoutez les variables suivantes (récupérez les infos de votre DB Railway) :
      - `APP_KEY` : (Générez-en une via `php artisan key:generate --show`)
      - `APP_ENV` : `production`
      - `APP_DEBUG` : `false`
      - `DB_CONNECTION` : `mysql`
      - `DB_HOST` : `${{MySQL.MYSQL_HOST}}` (Railway propose souvent des raccourcis)
      - `DB_PORT` : `${{MySQL.MYSQL_PORT}}`
      - `DB_DATABASE` : `${{MySQL.MYSQL_DATABASE}}`
      - `DB_USERNAME` : `${{MySQL.MYSQL_USER}}`
      - `DB_PASSWORD` : `${{MySQL.MYSQL_PASSWORD}}`
      - `SESSION_DRIVER` : `database`
      - `CACHE_STORE` : `database`
4.  **Commande de Build** : Railway détectera automatiquement le `Procfile`.
5.  **Migrations** : Pour lancer les migrations au démarrage, vous pouvez ajouter cette variable :
    - `RAILWAY_DOCKER_CMD_OVERRIDE` : `php artisan migrate --force && php artisan db:seed --class=RolesAndPermissionsSeeder && vendor/bin/heroku-php-nginx -C nginx.conf public/`

---

## ☁️ Option 2 : Render
Render est une excellente alternative gratuite.

### Étapes :
1.  **Nouveau Service** : Sur [Render.com](https://render.com), cliquez sur "New" -> "Web Service".
2.  **Configuration** :
    - **Runtime** : `PHP`
    - **Build Command** : `composer install --no-dev && npm install && npm run build`
    - **Start Command** : `vendor/bin/heroku-php-nginx -C nginx.conf public/`
3.  **Base de Données** : Créez une base de données MySQL ou PostgreSQL sur Render séparément.
4.  **Environment Variables** : Ajoutez les mêmes variables que pour Railway (voir ci-dessus).

---

## 🛠️ Optimisations Post-Déploiement

Une fois l'application en ligne, n'oubliez pas de :
1.  **Lien de stockage** : Railway/Render ne conservent pas les fichiers locaux après un redémarrage. Utilisez un service comme **AWS S3** ou **Cloudinary** si vous voulez stocker des images de produits de manière persistante.
2.  **SSL** : Ces plateformes fournissent le HTTPS automatiquement.
3.  **App Key** : Ne partagez jamais votre `APP_KEY` !

---

## 📋 Résumé des commandes utiles
- `php artisan config:cache` : Pour accélérer la lecture de la configuration.
- `php artisan route:cache` : Pour accélérer le routage.
- `php artisan view:cache` : Pour pré-compiler les vues Blade.
