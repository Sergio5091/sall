FROM php:8.2-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

COPY . .

# Create .env file if it doesn't exist
RUN if [ ! -f .env ]; then echo "APP_ENV=production" > .env; fi

RUN composer install --no-dev --optimize-autoloader
RUN npm install --legacy-peer-deps && npm run build

# Generate key only if .env exists and APP_KEY is empty
RUN php artisan key:generate --force || true
RUN php artisan config:cache
RUN php artisan route:cache

EXPOSE 9000

CMD ["php-fpm"]
