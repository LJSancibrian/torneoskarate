FROM php:8.2.0-apache

# Argumentos para personalizar UID y GID
ARG UID=1001
ARG GID=1000
ENV UID=${UID}
ENV GID=${GID}

# Configurar usuario y permisos
RUN groupmod -g $GID www-data && \
    usermod -u $UID -g $GID www-data && \
    chown -R $UID:www-data /var/www/html/ && \
    chmod -R 770 /var/www/html/ && \
    touch /var/log/php_errors.log && chown www-data:www-data /var/log/php_errors.log

# Exponer puertos
EXPOSE 80
EXPOSE 443

# Agregar instalador de extensiones PHP
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Instalar extensiones necesarias
RUN install-php-extensions @composer \
    pdo_mysql \
    mbstring \
    gd \
    zlib \
    bcmath \
    xml \
    fileinfo \
    imagick \
    mysqli

# Habilitar módulos de Apache
RUN a2enmod rewrite && a2enmod headers

# Configurar directorio de trabajo
WORKDIR /var/www/html
