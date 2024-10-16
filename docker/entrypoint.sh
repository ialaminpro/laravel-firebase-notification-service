#!/bin/bash

# Load environment variables from the .env file
export $(cat .env | grep -v ^# | xargs)

if [ ! -f "vendor/autoload.php" ]; then
    composer install --no-progress --no-interaction
fi

if [ ! -f ".env" ]; then
    echo "Creating env file for env $APP_ENV"
    cp .env.example .env
else
    echo "env file exists."
fi

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
until mysql -h db -P "${DB_PORT}" -u "${DB_USERNAME}" -p"${DB_PASSWORD}" -e "SHOW DATABASES;" 2>/dev/null; do
    >&2 echo "MySQL is unavailable - sleeping"
    sleep 2
done

echo "MySQL is up - executing migrations"

# Create the database if it does not exist
mysql -h db -P "${DB_PORT}" -u "${DB_USERNAME}" -p"${DB_PASSWORD}" -e "CREATE DATABASE IF NOT EXISTS ${DB_DATABASE};"

php artisan migrate
php artisan optimize
php artisan view:clear
php artisan route:clear

echo "Migrations successfully done"

# Start PHP-FPM
exec php-fpm

