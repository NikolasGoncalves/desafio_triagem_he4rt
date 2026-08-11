# ---- Etapa 1: dependências PHP ----

FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
COPY app-modules ./app-modules
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-interaction \
    --prefer-dist \
    --ignore-platform-reqs


# ---- Etapa 2: build dos assets (Vite/Tailwind) ----
FROM node:24-bookworm-slim AS frontend

WORKDIR /app

COPY package.json package-lock.json ./

RUN npm ci --ignore-scripts

COPY . .

# O Vite/Tailwind precisa dos arquivos do Filament
COPY --from=vendor /app/vendor ./vendor

RUN npx vite build


# ---- Etapa 3: imagem de runtime (PHP + Composer) ----
FROM php:8.4-cli-bookworm AS app

RUN apt-get update && apt-get install -y --no-install-recommends \
        libpq-dev \
        libicu-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libzip-dev \
        unzip \
        git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_pgsql \
        pgsql \
        intl \
        gd \
        bcmath \
        zip \
        opcache \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

# Reaproveita as dependências já instaladas
COPY --from=vendor /app/vendor ./vendor

COPY . .

# Assets gerados pelo Vite
COPY --from=frontend /app/public/build ./public/build

RUN composer dump-autoload --optimize \
    && cp -n .env.example .env \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

USER www-data

EXPOSE 10000

ENTRYPOINT ["entrypoint.sh"]
