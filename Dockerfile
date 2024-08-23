# Utiliser une image PHP officielle avec FPM
FROM php:8.1-fpm

# Installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    nginx

# Installer les extensions PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier le code de l'application
COPY . /var/www

# Définir le répertoire de travail
WORKDIR /var/www

# Installer les dépendances Composer
RUN composer install --no-dev --optimize-autoloader

# Configurer Nginx
COPY ./nginx/nginx.conf /etc/nginx/nginx.conf

# Exposer le port 8000
EXPOSE 8000

# Commande de démarrage
CMD ["sh", "-c", "php-fpm & nginx -g 'daemon off;'"]
