# #!/bin/sh

# # Attendre que la base de données soit disponible
# echo "Waiting for database connection..."
# until php artisan migrate:status > /dev/null 2>&1; do
#   echo "Waiting for PostgreSQL to be ready..."
#   sleep 3
# done

# # Exécuter les migrations
# php artisan migrate --force

# # Démarrer l'application
# exec "$@"
