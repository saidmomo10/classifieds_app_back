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

# Copier le script entrypoint.sh dans le conteneur et lui donner les droits d'exécution
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Définir le point d'entrée pour exécuter les migrations et seeders au démarrage
ENTRYPOINT ["/entrypoint.sh"]

# Exposer le port sur lequel l'application Laravel fonctionnera
EXPOSE 8000
