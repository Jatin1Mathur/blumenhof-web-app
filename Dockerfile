FROM yiisoftware/yii2-php:8.4-apache

# Which app to serve: frontend (shop) or backend (admin)
ARG APP=frontend

WORKDIR /app
COPY . /app

# Install PHP packages and set up production config
RUN composer install --no-dev --optimize-autoloader --no-interaction \
 && php init --env=Production --overwrite=All

# Create runtime folders (sessions, logs, cache) and let Apache write to them
RUN mkdir -p frontend/runtime/sessions backend/runtime/sessions console/runtime \
             frontend/web/assets backend/web/assets \
 && chown -R www-data:www-data frontend/runtime backend/runtime \
                               frontend/web/assets backend/web/assets

# Point Apache to the right web folder
RUN sed -i -e "s|/app/web|/app/${APP}/web|g" /etc/apache2/sites-available/000-default.conf

EXPOSE 80

# On every start: role tables, app tables, roles, guest role, admin account, then web server
CMD ["sh", "-c", "php yii migrate --migrationPath=@yii/rbac/migrations --interactive=0 && php yii migrate --interactive=0 && (php yii rbac/init || true) && php yii rbac/add-guest && php yii rbac/seed-admin && apache2-foreground"]
