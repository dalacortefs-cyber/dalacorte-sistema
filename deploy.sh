#!/bin/bash
# ─────────────────────────────────────────────────────────────────────────────
# Dalacorte Financial Solutions — Script de Deploy
# Executar no servidor: bash deploy.sh
# ─────────────────────────────────────────────────────────────────────────────

set -e

echo "🚀 Iniciando deploy Dalacorte Financial Solutions..."

# 1. Instalar dependências
echo "📦 Instalando dependências..."
composer install --no-dev --optimize-autoloader

# 2. Gerar APP_KEY
echo "🔑 Gerando APP_KEY..."
php artisan key:generate --force

# 3. Gerar JWT Secret
echo "🔐 Gerando JWT Secret..."
php artisan jwt:secret --force

# 4. Publicar configs dos pacotes
echo "📋 Publicando configurações..."
php artisan vendor:publish --provider="PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider" --force
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --force
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config --force
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider" --force

# 5. Rodar migrations
echo "🗄️  Rodando migrations..."
php artisan migrate --force

# 6. Rodar seeders
echo "🌱 Rodando seeders..."
php artisan db:seed --force

# 7. Criar link simbólico de storage
echo "🔗 Criando link de storage..."
php artisan storage:link

# 8. Otimizar
echo "⚡ Otimizando..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 9. Permissões
echo "🔒 Ajustando permissões..."
chmod -R 755 storage bootstrap/cache
chmod -R 644 .env

echo ""
echo "✅ Deploy concluído com sucesso!"
echo ""
echo "Credenciais padrão:"
echo "  Admin:      admin@dalacortefs.com.br    / Admin@2024"
echo "  Funcionário: funcionario@dalacortefs.com.br / Func@2024"
echo "  Cliente:    cliente@dalacortefs.com.br  / Cliente@2024"
echo ""
echo "⚠️  TROQUE AS SENHAS PADRÃO IMEDIATAMENTE!"
