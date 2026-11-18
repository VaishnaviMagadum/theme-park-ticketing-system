FROM php:8.1-apache

# Install MariaDB server, client, and utility tools
RUN apt-get update && apt-get install -y --no-install-recommends \
    mariadb-server \
    mariadb-client \
    dos2unix \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required for MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html/

# Fix line endings and grant execute permissions on entrypoint
RUN dos2unix /var/www/html/entrypoint.sh \
    && chmod +x /var/www/html/entrypoint.sh

# Pre-initialize MariaDB during Docker build
RUN mkdir -p /var/run/mysqld /var/lib/mysql \
    && chown -R mysql:mysql /var/run/mysqld /var/lib/mysql \
    && mysql_install_db --user=mysql --datadir=/var/lib/mysql \
    && (mysqld_safe --skip-syslog &) \
    && for i in $(seq 1 30); do mysqladmin ping --silent 2>/dev/null && break || sleep 1; done \
    && mysql -e "CREATE DATABASE IF NOT EXISTS tpts_db;" \
    && mysql tpts_db < /var/www/html/database/tpts_db.sql \
    && mysql -e "CREATE USER IF NOT EXISTS 'park_user'@'%' IDENTIFIED BY 'park123'; GRANT ALL PRIVILEGES ON *.* TO 'park_user'@'%' WITH GRANT OPTION; FLUSH PRIVILEGES;" \
    && mysqladmin shutdown

# Ensure web ownership
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80 10000

CMD ["/var/www/html/entrypoint.sh"]
