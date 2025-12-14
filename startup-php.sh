#!/bin/bash

echo "=== CineWave Laravel Startup ==="
cd /home/site/wwwroot

# Set permissions
chmod -R 755 storage bootstrap/cache 2>/dev/null || true

# Copy nginx default config to route to public folder
cat > /etc/nginx/sites-available/default <<'EOF'
server {
    listen 8080;
    listen [::]:8080;
    root /home/site/wwwroot/public;
    index index.php index.html index.htm;
    server_name _;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }
    
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

echo "Nginx config updated to serve from /public"

# Start services
service nginx restart
php-fpm
