# Utiliser une image PHP officielle avec FPM comme image de base
FROM php:8.3-fpm

# Installer les dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libicu-dev \
    libxml2-dev \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql intl xml zip

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www

# Copier le contenu de l'application Laravel dans le conteneur
COPY . .

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Lier le stockage
RUN php artisan storage:link

# Exécuter les migrations et seeders
RUN php artisan migrate --force \
    && php artisan db:seed --class=PermissionTableSeeder \
    && php artisan db:seed --class=CategoryTableSeeder \
    && php artisan db:seed --class=SubCategoryTableSeeder \
    && php artisan db:seed --class=CreateAdminUserSeeder

# Exposer le port sur lequel l'application Laravel fonctionnera
# EXPOSE 8000

# Commande pour démarrer le serveur Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0"]
