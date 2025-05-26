FROM php:8.2-apache

# Instala el driver de PostgreSQL y dependencias necesarias
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

# Copia todos los archivos del proyecto al directorio web de Apache
COPY . /var/www/html/

# Habilita el uso de .htaccess y mod_rewrite
RUN a2enmod rewrite

# Establece permisos apropiados
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto que usará Apache
EXPOSE 80