FROM dunglas/frankenphp:php8.4-bookworm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev \
    && docker-php-ext-install zip pdo_mysql \
    && apt-get clean

# Copy project files
COPY . /app
WORKDIR /app

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-interaction --no-progress --no-scripts \
    && composer run-script post-autoload-dump --no-interaction || true

# Create storage directories and set permissions
RUN mkdir -p storage/framework/{sessions,views,cache} bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Ensure Caddyfile exists (fallback in case it was missing accidentally)
RUN if [ ! -f Caddyfile ]; then \
        echo ':8000 {\n    root * /app/public\n    php_fastcgi /app/public\n    file_server\n    try_files {path} {path}/ /index.php?{query}\n    encode gzip\n}' > Caddyfile; \
    fi

EXPOSE 8000

CMD ["frankenphp", "run", "--config", "/app/Caddyfile"]
