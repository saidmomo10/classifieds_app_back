# Utiliser une image PHP officielle comme image parent
FROM php:8.3-fpm

# Installer les dépendances système nécessaires
RUN apt-get update && apt-get install -y libpq-dev libicu-dev libxml2-dev git unzip libzip-dev && \
    docker-php-ext-install pdo pdo_pgsql intl xml zip

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www

# Copier le contenu de votre application Laravel dans le conteneur
COPY . .

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Exposer le port sur lequel l'application fonctionnera
EXPOSE 8000

# Définir la commande pour exécuter l'application
CMD ["php-fpm"]
