FROM wordpress:6.9-php8.3

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    ca-certificates \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2.9 /usr/bin/composer /usr/bin/composer

# Install Node.js (LTS) and npm 10.x
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@11 \
    && rm -rf /var/lib/apt/lists/*

# Verify installations
RUN composer --version && node --version && npm --version

# Set working directory
WORKDIR /var/www/html

# Configure PHP upload limits
RUN echo "file_uploads=On" > /usr/local/etc/php/conf.d/uploads.ini \
 && echo "upload_max_filesize=64M" >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "post_max_size=64M" >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "memory_limit=256M" >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "max_execution_time=300" >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "max_input_time=300" >> /usr/local/etc/php/conf.d/uploads.ini