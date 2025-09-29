#!/bin/bash

echo "[INFO] Laravel scheduler loop starting..."

while true; do
  echo "[$(date)] Running Laravel schedule..." >> /var/log/scheduler.log
  php /var/www/artisan schedule:run >> /var/log/scheduler.log 2>&1
  sleep 60
done