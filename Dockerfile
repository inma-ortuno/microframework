FROM php:8.2-fpm

# Instalar extensiones necesarias para MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Instalar Nginx
RUN apt-get update && apt-get install -y nginx && apt-get clean

# Copiar configuración de Nginx
COPY nginx.conf /etc/nginx/nginx.conf

# Copiar el proyecto
COPY . /var/www/html/

# Permisos
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto 80
EXPOSE 80

# Iniciar PHP-FPM y Nginx
CMD nginx -t && php-fpm -F & nginx -g "daemon off;"
