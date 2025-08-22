# PHP 8.3 + Apache
FROM php:8.3-apache

# Extensões necessárias (Postgres/Supabase)
RUN apt-get update && apt-get install -y \
    git zip unzip libpq-dev \
 && docker-php-ext-install pdo_pgsql pgsql \
 && a2enmod rewrite

# DocumentRoot -> /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

WORKDIR /var/www/html
COPY . .

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Instalar dependências e otimizar (produção)
RUN composer install --no-dev --prefer-dist --optimize-autoloader \
 && php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache

# Porta padrão do Apache
EXPOSE 80
CMD ["apache2-foreground"]
