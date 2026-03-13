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

echo "- Creating default admin user..."
php bin/console app:create_admin

echo "Project setup completed successfully!"
