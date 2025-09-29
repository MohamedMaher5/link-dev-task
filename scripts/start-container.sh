#!/bin/bash

echo "Running Laravel setup commands..."

# wait until MySQL is ready 
# nc is the netcat command that allows you to open a TCP connection to a server
# -z flag means that nc should just scan for listening daemons, without sending any data to them
# db is the hostname of the MySQL container in docker compose file
# 3306 is the default MySQL port
# this is to make sure the mysql service is up and running before running the Laravel setup commands
until nc -z db 3306; do
  echo "Waiting for MySQL..."
  sleep 2
done

# Install Composer dependencies
# --no-interaction makes sure composer does not ask any interactive question
# --prefer-dist is recommended for production environments
# --optimize-autoloader makes autoloading faster by generating a class map to get all the classes that need to be included in a single file
composer install --no-interaction --prefer-dist --optimize-autoloader

# Laravel commands you want at container startup
php artisan key:generate
#php artisan passport:install
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link

#chmod -R 777 /var/www/storage/.


echo "Laravel is ready. Starting Supervisor..."


# fixing permissions for storage and bootstrap/cache directories
chown -R githubdeploy:githubdeploy storage bootstrap/cache vendor
chmod -R 777 storage bootstrap/cache vendor

#chown -R www-data:www-data /var/www

# Start Supervisor (which runs Nginx + PHP-FPM)
exec /usr/bin/supervisord -c /etc/supervisord.conf
