# ── Stage 1: build assets ────────────────────────────────────────────────────
FROM node:20-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# ── Stage 2: production image ─────────────────────────────────────────────────
FROM php:8.2-fpm-alpine AS production

# System dependencies
RUN apk add --no-cache \
    libpng-dev libjpeg-turbo-dev libwebp-dev freetype-dev \
    zip unzip icu-dev oniguruma-dev libxml2-dev \
    linux-headers pcre-dev git curl

# PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql bcmath gd intl mbstring xml pcntl posix opcache exif

# Redis PHP extension
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install PHP deps (no dev, optimised)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --prefer-dist

# Copy application code
COPY --chown=www-data:www-data . .

# Copy pre-built frontend assets
COPY --from=assets --chown=www-data:www-data /app/public/build ./public/build

# Run composer scripts now that the full source is present
RUN composer dump-autoload --optimize --no-dev

# PHP config
COPY docker/app/php.ini     /usr/local/etc/php/conf.d/99-laranews.ini
COPY docker/app/opcache.ini /usr/local/etc/php/conf.d/98-opcache.ini

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
