# ==========================================
# 1. BASE IMAGE
# ==========================================
# We start with a pre-configured image that already has PHP 8.2 and the Nginx web server installed.
# Using 'alpine' means it is a highly compressed, very lightweight Linux system (perfect for free tiers).
FROM webdevops/php-nginx:8.2-alpine

# ==========================================
# 2. SERVER CONFIGURATION
# ==========================================
# Laravel is strictly designed so that the actual website files live inside the '/public' folder.
# This variable tells the Nginx web server to route all incoming internet traffic directly to '/app/public'.
ENV WEB_DOCUMENT_ROOT=/app/public

# ==========================================
# 3. WORKING DIRECTORY
# ==========================================
# We set '/app' as our default folder inside the container. All subsequent commands will run here.
WORKDIR /app

# ==========================================
# 4. COPY YOUR CODE
# ==========================================
# This copies EVERYTHING from your Mac's current folder into the '/app' folder inside the container.
COPY . .

# ==========================================
# 5. INSTALL PHP PACKAGES (COMPOSER)
# ==========================================
# We install all the vendor dependencies. 
# The '--no-dev' flag tells composer NOT to install local testing utilities (like PHPUnit), saving memory on the free server.
# The '--optimize-autoloader' flag makes your website run faster.
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# ==========================================
# 6. FIX PERMISSIONS
# ==========================================
# Laravel needs to be able to create cache files, save user sessions, and write error logs.
# We grant 'application' (the user running the Nginx web server) ownership over the storage and cache folders.
RUN chown -R application:application /app/storage /app/bootstrap/cache
