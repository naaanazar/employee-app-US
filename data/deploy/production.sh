#!/usr/bin/env bash

git pull
composer install
comsposer update

cd ..

rm -f config/autoload/development.local.php
php vendor/doctrine/doctrine-module/bin/doctrine-module.php migrations:migrate --no-interaction