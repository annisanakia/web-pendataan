#!/bin/bash

git pull origin main

php artisan route:clear
php artisan route:cache
php artisan config:clear
php artisan cache:clear
php artisan optimize
