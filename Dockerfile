FROM php:8.1-apache

# Install MariaDB server and client
RUN apt-get update && apt-get install -y --no-install-recommends \
    mariadb-server \
    mariadb-client \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions required for MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html/

# Grant execute permissions on entrypoint
RUN chmod +x /var/www/html/entrypoint.sh

# Fix web ownership
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80 10000

CMD ["/var/www/html/entrypoint.sh"]
