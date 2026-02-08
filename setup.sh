#!/usr/bin/env bash

set -e

echo "Starting project setup..."

echo "1. Installing composer dependencies..."
composer install

echo "2. Configuring the database..."
php bin/console doctrine:schema:update --force

echo "3. Building assets..."
php bin/console tailwind:build

echo "4. Clearing the cache..."
php bin/console cache:clear

echo "Project setup completed successfully!"
