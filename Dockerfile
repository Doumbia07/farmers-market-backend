FROM dunglas/frankenphp:php8.4-bookworm

COPY . /app
WORKDIR /app

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installer les dépendances PHP
RUN composer install --optimize-autoloader --no-interaction

# Variables d'environnement
ENV PORT=8000

EXPOSE 8000

CMD ["frankenphp", "run", "--config", "/app/Caddyfile"]
