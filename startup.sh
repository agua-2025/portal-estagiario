#!/bin/bash

# Copia nosso arquivo de configuração personalizado do Nginx para o local correto
cp /home/site/wwwroot/default /etc/nginx/sites-available/default

# Reinicia o Nginx para que as alterações tenham efeito
service nginx restart