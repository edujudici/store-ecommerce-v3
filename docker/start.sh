#!/usr/bin/env bash

# rm -rf /etc/nginx
# cp -R /app/config/nginx /etc
service nginx restart

# rm -rf /etc/php82
# cp -R /app/config/php82 /etc/php82
service php-fpm82 restart

# Keep Container Running
tail -f /dev/null