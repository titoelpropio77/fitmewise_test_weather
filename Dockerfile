FROM php:8.3-apache

ARG WWWUSER=1000
ARG WWWGROUP=1000
WORKDIR /var/www/html

# Opcache settings
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="0" \
    PHP_OPCACHE_MAX_WASTED_PERCENTAGE="10"

# Instalar dependencias del sistema y extensiones PHP
RUN apt-get update && apt-get upgrade -y && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libzip-dev \
    libpq-dev \
    libicu-dev \
    g++ \
    zlib1g-dev \
    libonig-dev \
    libxml2-dev \
    nodejs \
    npm \
    supervisor \
    libmcrypt-dev \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        zip \
        intl \
        opcache \
    && apt-get clean

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configurar PHP
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini && \
    sed -i "s/memory_limit = .*/memory_limit = 512M/" /usr/local/etc/php/php.ini

# Configurar Apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN echo "ServerName laravel-app.local" >> /etc/apache2/apache2.conf && \
    sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    a2enmod rewrite headers

# Crear usuario para Laravel Octane (si lo usas)
RUN groupadd -g $WWWGROUP octane && \
    useradd -ms /bin/bash -u $WWWUSER -g $WWWGROUP octane

# Permisos
RUN chown -R $WWWUSER:$WWWGROUP /var/www && chmod -R 775 /var/www

# Copiar proyecto
COPY --chown=$WWWUSER:$WWWGROUP . /var/www/html

# Exponer puertos para Apache y Octane
EXPOSE 8500 5174
