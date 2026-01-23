FROM wordpress:6.9-php8.3

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    ca-certificates \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js (LTS) + npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest \
    && rm -rf /var/lib/apt/lists/*

# Verify installations
RUN composer --version && node --version && npm --version

# Set working directory
WORKDIR /var/www/html
