FROM php:8.1-apache

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enable Apache mod_rewrite and route /api to index.php
RUN a2enmod rewrite \
  && echo '<Directory /var/www/html>\n\
    AllowOverride All\n\
    RewriteEngine On\n\
    RewriteCond %{REQUEST_FILENAME} !-f\n\
    RewriteCond %{REQUEST_FILENAME} !-d\n\
    RewriteRule ^api/(.*)$ /api/index.php/$1 [L]\n\
  </Directory>' > /etc/apache2/conf-available/api.conf \
  && a2enconf api

WORKDIR /var/www/html

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader || true

EXPOSE 80


