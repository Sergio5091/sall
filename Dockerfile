FROM php:8.2-cli

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    default-mysql-client

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

COPY . .

# Create .env file for production
RUN cp .env.example .env
RUN sed -i 's/APP_ENV=local/APP_ENV=production/' .env
RUN sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env
RUN sed -i 's/DB_CONNECTION=sqlite/# DB_CONNECTION=sqlite/' .env
RUN sed -i 's/# DB_HOST=127.0.0.1/DB_HOST=${RENDER_DB_HOST}/' .env
RUN sed -i 's/# DB_PORT=3306/DB_PORT=${RENDER_DB_PORT}/' .env
RUN sed -i 's/# DB_DATABASE=laravel/DB_DATABASE=${RENDER_DB_NAME}/' .env
RUN sed -i 's/# DB_USERNAME=root/DB_USERNAME=${RENDER_DB_USER}/' .env
RUN sed -i 's/# DB_PASSWORD=/DB_PASSWORD=${RENDER_DB_PASSWORD}/' .env

# Set permissions
RUN chmod -R 777 storage bootstrap/cache

RUN composer install --no-dev --optimize-autoloader
RUN npm install --legacy-peer-deps && npm run build

# Generate key
RUN php artisan key:generate --force
RUN php artisan config:cache
RUN php artisan route:cache

# Run migrations
RUN php artisan migrate --force || true

EXPOSE 80

# Start PHP development server on port 80 for Render
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
