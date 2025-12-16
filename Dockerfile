FROM php:8.4-cli

WORKDIR /var/www/html

# Install system dependencies, Node.js/npm, Supervisor, and PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libzip-dev \
        sqlite3 \
        libsqlite3-dev \
        nodejs \
        npm \
        supervisor \
    && docker-php-ext-install \
        bcmath \
        pdo \
        pdo_mysql \
        pdo_sqlite \
        pcntl \
    && docker-php-ext-configure pcntl --enable-pcntl \
    && rm -rf /var/lib/apt/lists/*

# Install Composer (copied from the official Composer image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1

# Copy only composer files first for better layer caching
COPY composer.json composer.lock ./

# Install PHP dependencies (skip scripts; artisan not copied yet)
RUN composer install \
    --prefer-dist \
    --no-interaction \
    --no-progress \
    --no-scripts

# Copy the rest of the application
COPY . .
COPY .env.example .env

# Install JS dependencies and build assets
RUN npm install --force \
    && npm run build

EXPOSE 8000
EXPOSE 8080

# Supervisor config (manages reverb, queue workers, etc.)
COPY docker/supervisord.conf /etc/supervisor/supervisord.conf

# Entrypoint handles .env, key generation, migrations, then starts Supervisor
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Ensure storage, cache, and database directories are writable
RUN chown -R www-data:www-data storage bootstrap/cache database .env

USER www-data

CMD ["/entrypoint.sh"]


