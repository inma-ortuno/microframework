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

# Puerto que usará Nginx dentro del contenedor
ENV PORT=8080

# Exponer el puerto 8080
EXPOSE 8080

# Iniciar PHP-FPM y Nginx
CMD php-fpm -F & nginx -g "daemon off;"
