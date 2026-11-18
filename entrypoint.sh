#!/bin/bash
set -e

# Ensure MariaDB directories exist with correct permissions
mkdir -p /var/run/mysqld /var/lib/mysql
chown -R mysql:mysql /var/run/mysqld /var/lib/mysql
chmod 777 /var/run/mysqld

# Start MariaDB daemon in background
echo "Starting MariaDB daemon..."
mysqld_safe --skip-syslog --skip-networking=0 &

# Wait for MariaDB to respond
echo "Waiting for MariaDB..."
for i in {1..30}; do
    if mysqladmin ping --silent 2>/dev/null; then
        echo "MariaDB is up and running!"
        break
    fi
    sleep 1
done

# Fix MariaDB authentication so Apache/PHP (www-data) can connect
echo "Configuring database permissions..."
mysql --protocol=socket -u root << 'EOSQL' || true
CREATE DATABASE IF NOT EXISTS tpts_db;
CREATE USER IF NOT EXISTS 'park_user'@'%' IDENTIFIED BY 'park123';
CREATE USER IF NOT EXISTS 'park_user'@'localhost' IDENTIFIED BY 'park123';
CREATE USER IF NOT EXISTS 'park_user'@'127.0.0.1' IDENTIFIED BY 'park123';
GRANT ALL PRIVILEGES ON *.* TO 'park_user'@'%' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON *.* TO 'park_user'@'localhost' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON *.* TO 'park_user'@'127.0.0.1' WITH GRANT OPTION;
CREATE USER IF NOT EXISTS 'root'@'127.0.0.1' IDENTIFIED BY '';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;
EOSQL

# Ensure tpts_db has tables
TABLE_COUNT=$(mysql -N -s -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'tpts_db';" 2>/dev/null || echo 0)
if [ "$TABLE_COUNT" -eq 0 ]; then
    echo "Importing tpts_db.sql..."
    mysql tpts_db < /var/www/html/database/tpts_db.sql || true
    echo "Database import complete!"
fi

# Configure Apache port based on Render's $PORT
APP_PORT="${PORT:-80}"
echo "Configuring Apache to listen on port ${APP_PORT}..."
sed -i "s/Listen [0-9]*/Listen ${APP_PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost \*:${APP_PORT}>/g" /etc/apache2/sites-available/000-default.conf

# Start Apache in foreground
echo "Starting Apache web server..."
exec apache2-foreground
