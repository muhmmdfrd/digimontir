# Stage 1: Build dependensi PHP menggunakan Composer
FROM composer:2.7 AS vendor

WORKDIR /app

# Copy composer files terlebih dahulu untuk cache layer
COPY composer.json composer.lock* ./

# Install dependensi (tanpa dev package, tanpa interaksi, optimasi autoloader, abaikan platform reqs sementara)
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-reqs

# Copy keseluruhan kode aplikasi ke stage vendor
COPY . .

# Dump ulang autoloader
RUN composer dump-autoload --optimize --no-dev

# ======================================================================
# Stage 2: Build asset frontend menggunakan Node.js
FROM node:20-alpine AS frontend

WORKDIR /app

# Ambil resources dari package
COPY package.json package-lock.json* vite.config.js ./
# Copy seluruh resource asset
COPY resources/ ./resources/
COPY public/ ./public/

# Install depdendensi NPM dan build asset
RUN npm ci || npm install
RUN npm run build

# ======================================================================
# Stage 3: Runner menggunakan FrankenPHP (Base Image Minimal berbasis Alpine)
FROM dunglas/frankenphp:php8.4-alpine AS runner

# Set Environment Variables dasar Laravel
ENV APP_ENV=production \
    APP_DEBUG=false \
    SERVER_NAME=":80" \
    PHP_INI_SCAN_DIR=/usr/local/etc/php/conf.d

# Install dependensi sistem dan Ekstensi PHP khusus Laravel yang umum digunakan
# Gambar FrankenPHP Docker hadir dengan utilitas install-php-extensions
RUN install-php-extensions \
    pdo_mysql \
    pdo_pgsql \
    pgsql \
    pcntl \
    opcache \
    redis \
    gd \
    zip \
    intl \
    bcmath \
    exif \
    && rm -rf /var/cache/apk/*

WORKDIR /app

# Copy hasil build dari stage vendor (Dependencies & App source)
COPY --from=vendor /app /app

# Copy hasil build assets dari stage frontend (Vite Build) ke public/build
COPY --from=frontend /app/public/build /app/public/build

# Copy entrypoint script ke /usr/local/bin
COPY ./docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Atur perizinan file sesuai standar keamanan dan akses (www-data adalah grup default di alpine untuk web server)
RUN chmod +x /usr/local/bin/entrypoint.sh && \
    adduser -D -G www-data -s /bin/sh www-data || true && \
    chown -R www-data:www-data /app && \
    chmod -R 775 /app/storage /app/bootstrap/cache

# Buka akses port 80 untuk FrankenPHP
EXPOSE 80

# Mulai titik masuk ke sistem docker menggunakan entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Command utama yang mendasari jalannya FrankenPHP
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
