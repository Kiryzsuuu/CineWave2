#!/bin/bash

set -e

# Create custom nginx config
cat > /home/default <<'EOF'
server {
    listen 8080 default_server;
    listen [::]:8080 default_server;
    root /home/site/wwwroot;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
EOF

echo "[startup] Attempting to install nginx config..."

installed=0
for target in \
    /etc/nginx/sites-available/default \
    /etc/nginx/sites-enabled/default \
    /etc/nginx/conf.d/default.conf \
    /etc/nginx/conf.d/app.conf
do
    if cp /home/default "$target" 2>/dev/null; then
        echo "[startup] Installed nginx config at: $target"
        installed=1
        break
    fi
done

if [ "$installed" -eq 0 ]; then
    echo "[startup] WARNING: Could not write nginx config (permissions/path)."
else
    if nginx -t; then
        nginx -s reload || true
        echo "[startup] nginx reload attempted."
    else
        echo "[startup] WARNING: nginx config test failed; keeping existing config."
    fi
fi

echo "[startup] Startup complete"


