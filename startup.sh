#!/bin/bash

# Parte 1: Configura o servidor web (Nginx)
cp /home/site/wwwroot/default /etc/nginx/sites-available/default
service nginx restart

# Parte 2: Configura a aplicação Laravel (nossa solução)
cd /home/site/wwwroot
php artisan key:generate --force
php artisan config:cache
php artisan migrate --force