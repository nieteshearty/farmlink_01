# Use official PHP with Apache image
FROM php:8.2-apache

# Enable commonly used Apache modules
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy project files into the container
COPY . /var/www/html

# Ensure correct permissions for Apache
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \;

# Expose HTTP port
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]
