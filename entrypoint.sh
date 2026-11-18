#!/bin/bash
set -e

# Ensure mysqld directory exists and has proper permissions
mkdir -p /var/run/mysqld /var/lib/mysql
chown -R mysql:mysql /var/run/mysqld /var/lib/mysql
chmod 777 /var/run/mysqld

# Start MariaDB in the background
echo "Starting MariaDB service..."
mysqld_safe --skip-syslog --skip-networking=0 &

# Wait up to 30 seconds for MariaDB to become ready
echo "Waiting for MariaDB to be ready..."
for i in {1..30}; do
    if mysqladmin ping --silent 2>/dev/null; then
        echo "MariaDB is ready!"
        break
    fi
    sleep 1
done

# Ensure tpts_db exists and has tables
echo "Checking database..."
mysql -e "CREATE DATABASE IF NOT EXISTS tpts_db;"
TABLE_COUNT=$(mysql -N -s -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'tpts_db';" 2>/dev/null || echo 0)
if [ "$TABLE_COUNT" -eq 0 ]; then
    echo "Importing tpts_db.sql..."
    mysql tpts_db < /var/www/html/database/tpts_db.sql || true
    echo "Database imported!"
fi

# Configure Apache port based on Render's $PORT
APP_PORT="${PORT:-80}"
echo "Configuring Apache to listen on port ${APP_PORT}..."
sed -i "s/Listen [0-9]*/Listen ${APP_PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost \*:${APP_PORT}>/g" /etc/apache2/sites-available/000-default.conf

# Start Apache in foreground
echo "Starting Apache web server..."
exec apache2-foreground
