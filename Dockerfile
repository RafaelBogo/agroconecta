# PHP 8.3 com Apache
FROM php:8.3-apache

# 1) Pacotes do sistema + extensões necessárias
RUN apt-get update && apt-get install -y \
    git zip unzip libpq-dev \
 && docker-php-ext-install pdo_pgsql pgsql bcmath \
 && a2enmod rewrite

# 2) DocumentRoot -> /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 3) Código
WORKDIR /var/www/html
COPY . .

# 4) Composer bin
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 5) Corrige aviso do Git (opcional)
RUN git config --global --add safe.directory /var/www/html

# 6) Instalar deps e otimizar (produção)
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader \
 && php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache

EXPOSE 80
CMD ["apache2-foreground"]
