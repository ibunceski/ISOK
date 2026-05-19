# ─── Stage 1: Build PHP deps + frontend assets ───────────────────────────────
# Uses a fat image that has PHP, Composer AND Node so we can build everything.
# Nothing from this stage ends up in production except the compiled output.
FROM php:8.4-cli-alpine AS builder

# System libraries required to compile the PHP extensions and npm packages
RUN apk add --no-cache \
    bash \
    curl \
    unzip \
    git \
    postgresql-dev \
    libzip-dev \
    libxml2-dev \
    oniguruma-dev \
    icu-dev \
    nodejs \
    npm

# Compile the PHP extensions we actually use
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pgsql \
    mbstring \
    xml \
    bcmath \
    zip \
    intl

# Drop Composer binary from the official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# ── Composer: install deps ────────────────────────────────────────────────────
# Copy lockfiles first so this layer is cached unless dependencies change.
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --no-interaction

# Copy the rest of the application source
COPY . .

# Generate the optimised class-map.
# --no-scripts avoids running `php artisan package:discover`, which needs
# APP_KEY to be set — we handle that in the entrypoint at runtime instead.
RUN composer dump-autoload \
    --optimize \
    --no-dev \
    --no-scripts

# ── Node / Vite: build frontend assets ───────────────────────────────────────
# .npmrc already has ignore-scripts=true; npm ci honours the lock-file exactly.
RUN npm install && npm run build && rm -rf node_modules

# ─── Stage 2: Lean runtime image ─────────────────────────────────────────────
# Only contains PHP, the required extensions, and the compiled application.
# Node, npm, Composer, and all -dev headers are left behind.
FROM php:8.4-cli-alpine

# Runtime shared libraries (no -dev headers)
RUN apk add --no-cache \
    postgresql-client \
    icu-libs \
    oniguruma \
    libxml2 \
    libzip

# Install PHP extensions — temporarily pull in -dev packages, compile, then
# remove them to keep the final layer as small as possible.
RUN apk add --no-cache --virtual .build-deps \
        postgresql-dev \
        icu-dev \
        oniguruma-dev \
        libxml2-dev \
        libzip-dev \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        pgsql \
        mbstring \
        xml \
        bcmath \
        zip \
        intl \
        pcntl \
    && apk del .build-deps

WORKDIR /var/www/html

# Bring in everything built in Stage 1
COPY --from=builder /var/www/html /var/www/html

# Delete any locally-generated bootstrap cache files that were copied in from
# the build context. They may reference dev-only service providers (e.g.
# PailServiceProvider) that are not installed under --no-dev, causing a fatal
# "class not found" error on boot. They are regenerated at container start by
# `php artisan package:discover` in the docker-compose command.
RUN rm -f bootstrap/cache/*.php

# Ensure the framework-writable directories exist and are owned by www-data
RUN mkdir -p \
        storage/framework/views \
        storage/framework/cache \
        storage/framework/sessions \
        storage/logs \
        bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8080

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
