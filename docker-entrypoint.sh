#!/bin/bash
set -e

echo "🚀 Iniciando HealthNexus..."

# Generar key si no existe
php artisan key:generate --force

# Migrar base de datos
php artisan migrate --force

# Limpiar cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Crear storage link
php artisan storage:link 2>/dev/null || true

# Permisos
chown -R www-data:www-data storage bootstrap/cache

echo "✅ HealthNexus listo!"

# Iniciar Apache
apache2-foreground
