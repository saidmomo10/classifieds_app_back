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
    sqlite3 \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql intl xml zip

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www

# Copier le contenu de l'application Laravel dans le conteneur
COPY . .

# ➕ Ajouter temporairement un .env de build pour éviter les erreurs
COPY .env.build .env

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Supprimer le .env temporaire après installation
RUN rm .env

# ✅ Lier le dossier de stockage après exécution (à faire au moment du run)
# Ne pas exécuter ici : artisan dépend des vraies variables .env

# Commande exécutée à l'exécution du conteneur
CMD php artisan config:clear && \
    php artisan migrate:fresh --seed --force && \
    php artisan storage:link && \
    php artisan serve --host=0.0.0.0 --port=8000
