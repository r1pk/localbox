FROM php:8.4-fpm AS base

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

COPY --from=composer:2.8.12 /usr/bin/composer /usr/local/bin/composer
COPY --from=node:25.2-bookworm /usr/local/bin /usr/local/bin
COPY --from=node:25.2-bookworm /usr/local/lib/node_modules /usr/local/lib/node_modules

RUN apt-get update \
    && apt-get install -y bash curl git unzip zip \
    && install-php-extensions gd intl opcache pdo pdo_mysql xdebug
