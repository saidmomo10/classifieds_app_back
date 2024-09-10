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

# Copier les fichiers de configuration Nginx (si applicable)
# COPY ./nginx/default.conf /etc/nginx/conf.d/

# Exposer le port sur lequel l'application Laravel fonctionnera
EXPOSE 8000

# Commande pour démarrer le serveur Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
