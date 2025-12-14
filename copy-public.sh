#!/bin/bash
echo "=== Copying public files to root ==="
cd /home/site/wwwroot

if [ -d "public" ]; then
    # Copy all public files to root
    cp -r public/* ./
    cp public/.htaccess ./ 2>/dev/null || true
    echo "✓ Files copied from public/ to root"
    ls -la index.php
fi

echo "=== Ready to serve ===" 
