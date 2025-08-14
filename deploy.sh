#!/bin/bash

# --- Script de Deploy Automatizado ---

# Nome da sua pasta de projeto no servidor
PROJECT_DIR="portal-estagiario"

# URL de acesso ao site
APP_URL="https://portaldoestagiario.com"

# --- Parte 1: Preparar e Enviar Mudanças do Local para o GitHub ---
echo "--- 1/4: Gerando assets de producao e enviando para o GitHub ---"
# O npm run build já foi feito localmente

# Assegure que as mudancas estao no git
git status
git add .
git commit -m "Deploy: Atualizando o site"
git push origin main
echo "--- Finalizado envio para o GitHub. ---"

# --- Parte 2: Fazer o Deploy no Servidor via SSH ---
echo "--- 2/4: Conectando via SSH e atualizando o servidor ---"
ssh por36227@portaldoestagiario.com << EOF
    # Ir para o diretório do projeto
    cd /home3/por36227/$PROJECT_DIR

    # Puxar as últimas alterações do GitHub
    git pull

    # Instalar dependências e migrar o banco de dados
    composer install --no-dev
    php artisan migrate
    php artisan optimize:clear

    # Copiar os arquivos públicos do projeto para a public_html
    cp -r public/* ../public_html/

    # Reajustar as permissoes
    chmod -R 775 storage
    chmod -R 775 bootstrap/cache
    chmod -R 755 public
    
EOF

echo "--- 3/4: Deploy concluido no servidor. ---"

# --- Parte 4: Limpeza final de cache no navegador ---
echo "--- 4/4: Finalizando deploy. Limpe o cache do seu navegador para ver as mudancas! ---"
echo "Deploy finalizado com sucesso! Acesse $APP_URL"