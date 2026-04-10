#!/bin/sh
set -e

# Pesan informasi
echo "🚀 Memulai deployment prosedur..."

# Jalankan migrasi database
echo "⏳ Menjalankan database migrations..."
php artisan migrate

echo "⏳ Menjalankan database seeder..."
php artisan db:seed

# Cache konfigurasi untuk produksi
echo "📦 Caching konfigurasi Laravel (config, routes, views, events)..."
php artisan optimize:clear
php artisan optimize

# Eksekusi command utama (FrankenPHP)
echo "✅ Menjalankan server FrankenPHP..."
exec "$@"
