FROM dunglas/frankenphp:php8.4-bookworm

COPY . /app
WORKDIR /app

RUN composer install --optimize-autoloader --no-interaction

CMD ["frankenphp", "run", "--config", "/app/Caddyfile"]
