FROM php:8.2-apache

# Desactivar MPM event para evitar conflicto
RUN a2dismod mpm_event

# Instalar extensiones necesarias para MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Copiar el proyecto al servidor Apache
COPY . /var/www/html/

# Dar permisos a Apache
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
