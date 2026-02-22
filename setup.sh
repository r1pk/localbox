#!/usr/bin/env bash

set -e
cd "$(dirname "$0")"

echo "Starting project setup..."

echo "- Installing composer dependencies..."
composer install

echo "- Updating database schema..."
php bin/console doctrine:schema:update --force

echo "- Building tailwind assets..."
php bin/console tailwind:build

echo "- Clearing application cache..."
php bin/console cache:clear

echo "- Loading data fixtures..."
php bin/console doctrine:fixtures:load --no-interaction

echo "Project setup completed successfully!"
