#!/usr/bin/env bash

git pull
composer install
composer update

rm -f config/autoload/doctrine.local.php
php vendor/doctrine/doctrine-module/bin/doctrine-module.php migrations:migrate --no-interaction