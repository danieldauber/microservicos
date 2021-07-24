#!/bin/bash

### FRONT-END
npm config set cache /var/www/.npm-cache --global
cd /var/www/frontend && npm install && cd ..

cd backend

composer install

php artisan key:generate
php artisan migrate
php-fpm
