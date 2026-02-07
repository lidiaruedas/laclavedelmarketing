FROM php:8.2-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Activar mod_rewrite
RUN a2enmod rewrite

# Configurar DocumentRoot a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copiar archivos del proyecto
COPY . /var/www/html

# Permisos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
