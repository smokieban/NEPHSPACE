# Use official PHP image with Apache so .php endpoints are executed
FROM php:8.2-apache

# Install required PHP extensions used by the site
RUN apt-get update \
    && apt-get install -y --no-install-recommends libonig-dev \
    && docker-php-ext-install mbstring \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Copy Apache site configuration with extensionless route support
COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf

# Copy website files
COPY . /var/www/html

# Fix basic ownership for Apache runtime
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

CMD ["apache2-foreground"]