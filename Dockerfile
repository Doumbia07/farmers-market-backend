FROM dunglas/frankenphp:php8.4-bookworm

# Installer les dépendances système (git, unzip, zip) et l'extension PHP zip
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    && docker-php-ext-install zip \
    && apt-get clean

# Copie des fichiers du projet
COPY . /app
WORKDIR /app

# Téléchargement de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installation des dépendances PHP
RUN composer install --optimize-autoloader --no-interaction --no-progress

# Permissions sur les dossiers de stockage
RUN mkdir -p storage/framework/{sessions,views,cache} bootstrap/cache && chmod -R 775 storage bootstrap/cache

# Exposition du port
EXPOSE 8000

# Lancement avec FrankenPHP
CMD ["frankenphp", "run", "--config", "/app/Caddyfile"]
