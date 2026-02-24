FROM php:8.4-fpm AS base

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

COPY --from=composer:2.8.12 /usr/bin/composer /usr/local/bin/composer

RUN apt-get update \
    && apt-get install -y bash curl git unzip zip \
    && install-php-extensions gd intl opcache pdo pdo_mysql xdebug

RUN mkdir -p /var/www/storage \
    && chown -R www-data:www-data /var/www/storage \
    && chmod 775 /var/www/storage
