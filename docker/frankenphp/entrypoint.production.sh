#!/usr/bin/env bash

set -e
cd "$(dirname "$0")"

php bin/console doctrine:schema:update --force --no-interaction
php bin/console app:create_admin --no-interaction

exec docker-php-entrypoint "$@"
