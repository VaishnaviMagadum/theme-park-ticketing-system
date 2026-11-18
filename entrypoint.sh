#!/bin/bash
set -e

# If no external DB_HOST is provided, start local MariaDB
if [ -z "$DB_HOST" ] || [ "$DB_HOST" = "localhost" ] || [ "$DB_HOST" = "127.0.0.1" ]; then
    echo "Starting local MariaDB service..."
    service mariadb start

    # Wait for MariaDB to be ready
    until mysqladmin ping --silent; do
        sleep 1
    done

    echo "Checking tpts_db database..."
    mysql -e "CREATE DATABASE IF NOT EXISTS tpts_db;"
    TABLE_COUNT=$(mysql -N -s -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'tpts_db';")
    if [ "$TABLE_COUNT" -eq 0 ]; then
        echo "Importing baseline schema from database/tpts_db.sql..."
        mysql tpts_db < /var/www/html/database/tpts_db.sql
        echo "Database imported successfully!"
    fi
fi

# Configure Apache port based on Render's $PORT environment variable
APP_PORT="${PORT:-80}"
echo "Configuring Apache to listen on port ${APP_PORT}..."
sed -i "s/Listen 80/Listen ${APP_PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${APP_PORT}>/g" /etc/apache2/sites-available/000-default.conf

# Start Apache in the foreground
echo "Starting Apache web server..."
exec apache2-foreground
