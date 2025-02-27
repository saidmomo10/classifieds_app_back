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

# Copier le script wait-for-it.sh dans le conteneur
COPY wait-for-it.sh /wait-for-it.sh
RUN chmod +x /wait-for-it.sh

# Exposer le port sur lequel l'application Laravel fonctionnera
# EXPOSE 8000

# Attendre que PostgreSQL soit prêt avant d'exécuter les migrations et les seeders
RUN /wait-for-it.sh postgresql:5432 -- php artisan migrate --force
RUN /wait-for-it.sh postgresql:5432 -- php artisan db:seed --class=PermissionTableSeeder
RUN /wait-for-it.sh postgresql:5432 -- php artisan db:seed --class=CategoryTableSeeder
RUN /wait-for-it.sh postgresql:5432 -- php artisan db:seed --class=SubCategoryTableSeeder
RUN /wait-for-it.sh postgresql:5432 -- php artisan db:seed --class=CreateAdminUserSeeder

# Lier le répertoire de stockage de Laravel
RUN php artisan storage:link

# Commande pour démarrer l'application Laravel
CMD php artisan serve --host=0.0.0.0
