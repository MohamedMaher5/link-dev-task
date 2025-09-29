FROM nginx:alpine

# Set working directory
WORKDIR /var/www

# Add user for laravel application
RUN adduser -D -H -u 82 -s /bin/bash www-data -G www-data

COPY ./code /var/www
COPY ./docker/nginx/conf.d/ /etc/nginx/conf.d
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf

EXPOSE 80
EXPOSE 433

