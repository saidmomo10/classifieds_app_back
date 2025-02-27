#!/bin/sh

# Attendre que la base de données soit prête (optionnel, remplace `db` par ton service DB)
until nc -z -v -w30 db 3306; do
  echo "⏳ En attente de la base de données..."
  sleep 5
done
echo "✅ Base de données prête !"

# Exécuter les migrations et seeders
php artisan migrate --force
php artisan db:seed --class=PermissionTableSeeder
php artisan db:seed --class=CategoryTableSeeder
php artisan db:seed --class=SubCategoryTableSeeder
php artisan db:seed --class=CreateAdminUserSeeder

# Lancer Laravel
exec php artisan serve --host=0.0.0.0 --port=8000
