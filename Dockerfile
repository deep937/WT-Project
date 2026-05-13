FROM php:8.1-apache

# Install extensions required for MySQL and common utilities
RUN apt-get update \
    && apt-get install -y --no-install-recommends libzip-dev unzip git zlib1g-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite
RUN a2enmod rewrite

# Copy application code
COPY . /var/www/html/

# Ensure proper permissions (www-data user)
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Add entrypoint script to allow Render to use $PORT
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["apache2-foreground"]
