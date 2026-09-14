FROM dunglas/frankenphp:1-php8.4-trixie AS base

COPY --from=composer:2.8.12 /usr/bin/composer /usr/local/bin/composer

RUN install-php-extensions gd intl opcache pdo pdo_sqlite xdebug

RUN mkdir -p /storage \
    && chown -R www-data:www-data /storage \
    && chmod 775 /storage

WORKDIR /app
