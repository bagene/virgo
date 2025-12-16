FROM php:8.4-cli

WORKDIR /var/www/html

# Install system dependencies, Node.js/npm, and PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libzip-dev \
        sqlite3 \
        libsqlite3-dev \
        nodejs \
        npm \
    && docker-php-ext-install \
        bcmath \
        pdo \
        pdo_mysql \
        pdo_sqlite \
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

# Install JS dependencies and build assets
RUN npm install --force \
    && npm run build

ENV APP_ENV=local \
    APP_DEBUG=true \
    APP_URL=http://localhost:8000

EXPOSE 8000

# Entrypoint handles .env, key generation, migrations, then starts the dev server
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh \
    && chown www-data:www-data /entrypoint.sh

# Ensure storage, cache, and database directories are writable
RUN chown -R www-data:www-data storage bootstrap/cache database

USER www-data

CMD ["/entrypoint.sh"]


