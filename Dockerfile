FROM php:8.2-apache

# Instala el driver de PostgreSQL y dependencias necesarias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copia todos los archivos del proyecto al directorio web de Apache
COPY . /var/www/html/

# Habilita el uso de .htaccess y mod_rewrite
RUN a2enmod rewrite

# Establece permisos apropiados
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Configuración de PHP para producción
RUN echo "display_errors = Off" >> /usr/local/etc/php/php.ini \
    && echo "log_errors = On" >> /usr/local/etc/php/php.ini \
    && echo "error_log = /var/log/apache2/php_errors.log" >> /usr/local/etc/php/php.ini

# Exponer el puerto que usará Apache
EXPOSE 80

# Comando por defecto
CMD ["apache2-foreground"]