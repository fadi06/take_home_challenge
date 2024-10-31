FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html/

# Install dependencies for the operating system software
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    libzip-dev \
    unzip \
    git \
    libonig-dev \
    curl \
    mariadb-client \
    samba \
    smbclient

# Install npm
RUN apt-get update && apt-get install -y npm && npm install -g n && npm install -g npm@latest

RUN n lts

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install composer (php package manager)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install phpredis extension to use redis as queue server for laravel project
RUN curl https://github.com/FriendsOfPHP/pickle/releases/latest/download/pickle.phar --output pickle.phar && chmod +x pickle.phar && mv pickle.phar /usr/bin/pickle
RUN pecl install igbinary && docker-php-ext-enable igbinary
RUN pecl install redis && docker-php-ext-enable redis

# Install extensions for php
RUN docker-php-ext-install pdo_mysql mbstring zip exif
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
RUN docker-php-ext-install pcntl
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Install supervisor and cron
RUN apt-get update && apt-get install -y supervisor cron

# Check if MySQL group exists, or use a fallback GID if GID 999 is taken
RUN if ! getent group mysql > /dev/null 2>&1; then \
    if ! getent group 999 > /dev/null 2>&1; then \
    groupadd -g 999 mysql; \
    else \
    groupadd -g 1001 mysql; \
    fi \
    fi && \
    if ! id -u mysql > /dev/null 2>&1; then \
    useradd -r -u 999 -g mysql mysql || useradd -r -u 1001 -g mysql mysql; \
    fi

# Fix for MySQL PID file location issue
# Create a secure directory for MySQL PID file and adjust permissions
RUN mkdir -p /var/run/mysqld && \
    chown -R mysql:mysql /var/run/mysqld && \
    chmod 755 /var/run/mysqld

# Copy start container script and give executable permissions
COPY ./start-container.sh /usr/bin/start-container
RUN chmod +x /usr/bin/start-container

# Create a user for the app
RUN useradd -u1000 docker_app_user
RUN adduser www-data docker_app_user
RUN mkdir /home/docker_app_user
RUN chown -R docker_app_user:docker_app_user /home/docker_app_user

# Add cron job for Laravel schedule
RUN echo "* * * * * root php /var/www/html/artisan schedule:run >> /var/www/html/storage/logs/cron.log 2>&1" >> /etc/crontab

# Set working directory
WORKDIR /var/www/html/

# Ensure storage and cache directories exist with the correct permissions
RUN mkdir -p /var/www/html/storage/framework/cache/data \
    && mkdir -p /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage \
    && chown -R www-data:www-data /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Copy application code
COPY . /var/www/html/

# Samba configuration (optional configuration below)
# Add a shared directory for Samba
RUN mkdir -p /samba/share
RUN chown -R docker_app_user:docker_app_user /samba/share

# Add basic Samba configuration (can be extended as needed)
RUN echo '[samba]\n\
    path = /samba/share\n\
    browseable = yes\n\
    writable = yes\n\
    guest ok = yes\n\
    read only = no' >> /etc/samba/smb.conf

# Expose port 9000 for php-fpm and port 445 for SMB (Samba)
EXPOSE 9000 445

# Start container with php-fpm and Samba
ENTRYPOINT ["start-container"]
