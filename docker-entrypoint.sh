# #!/bin/bash

# # Attendre que la base de données soit disponible avant de lancer les migrations
# until php -r "new PDO('mysql:host=${DB_HOST};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');" 2>/dev/null; do
#     echo "Waiting for MySQL database connection..."
#     sleep 5php artisan migrate:reset --force
# done

# # Lancer les migrations

# php artisan migrate --force
# php artisan storage:link

RUN php artisan db:seed --class=PermissionTableSeeder
RUN php artisan db:seed --class=CategoryTableSeeder
RUN php artisan db:seed --class=SubCategoryTableSeeder
RUN php artisan db:seed --class=CreateAdminUserSeeder

# # Exécuter la commande donnée
# exec "$@"
