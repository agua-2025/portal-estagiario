#!/bin/bash

# Configura o servidor web Nginx (Isso é rápido e deve ficar)
cp /home/site/wwwroot/default /etc/nginx/sites-available/default
service nginx restart

# Comandos do Laravel - DESATIVADOS TEMPORARIAMENTE
# Vamos executá-los manualmente depois que o servidor estiver estável.
# cd /home/site/wwwroot
# php artisan key:generate --force
# php artisan config:cache
# php artisan migrate --force