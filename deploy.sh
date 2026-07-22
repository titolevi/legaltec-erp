#!/bin/bash
# 🚀 Script de despliegue — Legaltec ERP en GoDaddy
# Ejecutar desde cPanel Terminal o SSH

echo "=== LEGALTEC ERP - DEPLOY ==="

# 1. Ir al directorio público
cd ~/public_html || cd ~/public_html/legaltec || mkdir -p ~/public_html && cd ~/public_html

# 2. Clonar el proyecto (solo la primera vez)
if [ ! -f "artisan" ]; then
    echo "📥 Clonando repositorio..."
    git clone https://github.com/titolevi/legaltec-erp.git .
else
    echo "📥 Actualizando repositorio..."
    git pull
fi

# 3. Instalar dependencias de PHP
echo "📦 Instalando dependencias..."
composer install --no-dev --optimize-autoloader

# 4. Copiar .env
if [ ! -f ".env" ]; then
    echo "⚙️ Configurando .env..."
    cp .env.example .env
    php artisan key:generate
fi

# 5. Migrar base de datos
echo "🗄️ Migrando base de datos..."
php artisan migrate --force

# 6. Cachear config
echo "⚡ Optimizando..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Permisos
echo "🔐 Ajustando permisos..."
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs

echo ""
echo "✅ DESPLIEGUE COMPLETADO"
echo "🌐 http://legaltec.pe"