# ==== base ====
FROM dunglas/frankenphp:1-php8.4-trixie AS base

RUN install-php-extensions gd intl opcache pdo pdo_sqlite

RUN mkdir -p /storage \
    && chown -R www-data:www-data /storage \
    && chmod 775 /storage

WORKDIR /app
# ==== base ====

# ==== development ====
FROM base AS development

COPY --from=composer:2.8.12 /usr/bin/composer /usr/local/bin/composer

RUN install-php-extensions xdebug
# ==== development ====

# ==== builder ====
FROM base AS builder

ENV APP_ENV=prod

COPY --from=composer:2.8.12 /usr/bin/composer /usr/local/bin/composer

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip \
    && rm -rf /var/lib/apt/lists/*

COPY composer.json composer.lock symfony.lock ./
RUN composer install \
        --no-dev \
        --no-scripts \
        --no-interaction \
        --prefer-dist \
        --optimize-autoloader

COPY . .
RUN cp .env.default .env \
    && composer dump-autoload --no-dev --optimize --classmap-authoritative \
    && php bin/console assets:install \
    && php bin/console importmap:install \
    && php bin/console tailwind:build --minify \
    && php bin/console asset-map:compile \
    && php bin/console cache:warmup
# ==== builder ====

# ==== production ====
FROM base AS production

ENV APP_ENV=prod

COPY docker/frankenphp/conf.d/php.production.ini /usr/local/etc/php/conf.d/php.production.ini
COPY docker/frankenphp/Caddyfile /etc/frankenphp/Caddyfile

COPY --from=builder --chown=www-data:www-data /app /app

COPY --chmod=0755 docker/frankenphp/entrypoint.production.sh /usr/local/bin/entrypoint.production.sh
ENTRYPOINT ["entrypoint.production.sh"]

CMD ["--config", "/etc/frankenphp/Caddyfile", "--adapter", "caddyfile"]
# ==== production ====
