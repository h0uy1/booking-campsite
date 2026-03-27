# ==========================================
# 1. BASE IMAGE
# ==========================================
# We start with a pre-configured image that already has PHP 8.3 and the Nginx web server installed.
# We upgraded to 8.3/8.4 to match the local PHP version installed on your Mac!
FROM webdevops/php-nginx:8.3-alpine

# ==========================================
# 2. SERVER CONFIGURATION
# ==========================================
# Tell Nginx to serve the site strictly from '/public'.
ENV WEB_DOCUMENT_ROOT=/app/public

# Prevent memory crashes when downloading packages on Free cloud tiers
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_MEMORY_LIMIT=-1

WORKDIR /app
COPY . .

# ==========================================
# 3. INSTALL PHP PACKAGES (COMPOSER)
# ==========================================
# We added '--ignore-platform-reqs' to bypass strict version checks if a package demands PHP 8.4 exactly.
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev --ignore-platform-reqs

# ==========================================
# 4. FIX PERMISSIONS
# ==========================================
# Grant ownership of the storage and cache to the web server user.
RUN chown -R application:application /app/storage /app/bootstrap/cache
