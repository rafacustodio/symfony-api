#!/bin/bash

php /usr/bin/composer update
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

exec "$@"