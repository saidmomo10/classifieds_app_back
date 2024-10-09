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

# Copier le script d'entrée
COPY docker-entrypoint.sh /usr/local/bin/

# Donner des droits d'exécution au script
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Attendre la disponibilité de la base de données et exécuter les migrations
RUN php artisan migrate --force

# Exécuter les seeders
RUN php artisan db:seed --class=PermissionTableSeeder
RUN php artisan db:seed --class=CategoryTableSeeder
RUN php artisan db:seed --class=SubCategoryTableSeeder
RUN php artisan db:seed --class=CreateAdminUserSeeder

# Créer le lien de stockage
RUN php artisan storage:link

# Exposer le port sur lequel l'application Laravel fonctionnera
EXPOSE 8000

# Commande pour démarrer l'application Laravel avec le script d'entrée
ENTRYPOINT ["docker-entrypoint.sh"]

# Commande pour démarrer le serveur Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
