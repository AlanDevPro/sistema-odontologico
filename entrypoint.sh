#!/bin/sh
# entrypoint.sh

echo "Preparando el entorno de Laravel para Lalysdent..."

# Ejecutamos las migraciones forzadas para crear las tablas en Oracle
php artisan migrate --force

# Limpiamos y optimizamos las cachés de configuración
php artisan config:clear
php artisan cache:clear

# --- NUEVO: Procesamiento de Tailwind CSS ---
echo "Instalando dependencias de NPM..."
npm install

echo "Compilando estilos y recursos con Vite..."
# Usamos 'build' para generar los archivos estáticos listos para producción/desarrollo
npm run build
# ------------------------------------------

echo "Iniciando PHP-FPM..."
# Ejecuta el comando principal (php-fpm) para que interactúe con Nginx
exec "$@"