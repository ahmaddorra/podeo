
FROM php:8.0-apache

#RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# 1. Install development packages and clean up apt cache.
RUN apt-get update && apt-get install -y \
    curl \
    libonig-dev \
    g++ \
    git \
    libbz2-dev \
    libfreetype6-dev \
    libicu-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libpng-dev \
    libreadline-dev \
    libzip-dev \
    sudo \
    unzip \
    zip \
 && rm -rf /var/lib/apt/lists/*

#RUN chmod -R 755 ./
#COPY ./apache2.conf /etc/apache2
# 2. Apache configs + document root.
COPY / /var/www/podeo
RUN sudo chmod -R 777 /var/www/podeo/

COPY /apache/site-default.conf /etc/apache2/sites-available/000-default.conf
COPY /apache/apache2.conf /etc/apache2/apache2.conf

RUN echo "ServerName podeo.local" >> /etc/apache2/apache2.conf

ENV APACHE_DOCUMENT_ROOT=/var/www/podeo
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf


# 3. mod_rewrite for URL rewrite and mod_headers for .htaccess extra headers like Access-Control-Allow-Origin-
RUN a2enmod rewrite headers

# 4. Start with base PHP config, then add extensions.
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN docker-php-ext-install \
    bcmath \
    bz2 \
    calendar \
    iconv \
    intl \
    mbstring \
    opcache \
    pdo \
    pdo_mysql \
    zip

# 5. Composer.
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer



# 6. We need a user with the same UID/GID as the host user
# so when we execute CLI commands, all the host file's permissions and ownership remain intact.
# Otherwise commands from inside the container would create root-owned files and directories.
ARG uid=1000
RUN useradd -G www-data,root -u $uid -d /home/devuser devuser
RUN mkdir -p /home/devuser/.composer && \
    chown -R devuser:devuser /home/devuser



#COPY ./start.sh /
#RUN chmod +x /start.sh
#ENTRYPOINT ["/start.sh"]
#CMD ["/start.sh"]
