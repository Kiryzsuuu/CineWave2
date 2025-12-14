# CineWave - Azure Deployment Guide

## Prerequisites
- Azure Account
- Azure CLI installed
- Git installed
- MongoDB Atlas account (already configured)

## Deployment Steps

### 1. Login to Azure
```bash
az login
```

### 2. Create Resource Group
```bash
az group create --name CineWave-RG --location southeastasia
```

### 3. Create App Service Plan
```bash
az appservice plan create --name CineWave-Plan --resource-group CineWave-RG --sku B1 --is-linux
```

### 4. Create Web App
```bash
az webapp create --resource-group CineWave-RG --plan CineWave-Plan --name cinewave-app --runtime "PHP:8.2"
```

### 5. Configure Application Settings
```bash
# Set APP_KEY
az webapp config appsettings set --resource-group CineWave-RG --name cinewave-app --settings APP_KEY="<your-app-key>"

# Set environment to production
az webapp config appsettings set --resource-group CineWave-RG --name cinewave-app --settings APP_ENV=production

# Set debug to false
az webapp config appsettings set --resource-group CineWave-RG --name cinewave-app --settings APP_DEBUG=false

# Set MongoDB connection
az webapp config appsettings set --resource-group CineWave-RG --name cinewave-app --settings DB_CONNECTION=mongodb

az webapp config appsettings set --resource-group CineWave-RG --name cinewave-app --settings DB_URI="<your-mongodb-uri>"

az webapp config appsettings set --resource-group CineWave-RG --name cinewave-app --settings DB_DATABASE=cinewave

# Set session driver
az webapp config appsettings set --resource-group CineWave-RG --name cinewave-app --settings SESSION_DRIVER=file

# Set cache driver
az webapp config appsettings set --resource-group CineWave-RG --name cinewave-app --settings CACHE_STORE=file
```

### 6. Configure Deployment
```bash
# Configure local Git deployment
az webapp deployment source config-local-git --resource-group CineWave-RG --name cinewave-app

# Get deployment credentials
az webapp deployment list-publishing-credentials --resource-group CineWave-RG --name cinewave-app --query "{Username:publishingUserName, Password:publishingPassword}"
```

### 7. Configure Startup Command
```bash
az webapp config set --resource-group CineWave-RG --name cinewave-app --startup-file "bash startup.sh"
```

### 8. Enable MongoDB Extension
```bash
# SSH into the app and install MongoDB extension
az webapp ssh --resource-group CineWave-RG --name cinewave-app

# Then run:
# pecl install mongodb
# echo "extension=mongodb.so" >> /usr/local/etc/php/conf.d/mongodb.ini
```

### 9. Deploy Code
```bash
# Add Azure remote
git remote add azure https://<username>@cinewave-app.scm.azurewebsites.net/cinewave-app.git

# Push to Azure
git push azure main
```

### 10. Set Up Storage Permissions
```bash
az webapp config appsettings set --resource-group CineWave-RG --name cinewave-app --settings POST_BUILD_COMMAND="chmod -R 755 /home/site/wwwroot/storage /home/site/wwwroot/bootstrap/cache"
```

### 11. Run Migrations (if needed)
```bash
# SSH into the app
az webapp ssh --resource-group CineWave-RG --name cinewave-app

# Run migrations
cd /home/site/wwwroot
php artisan migrate --force
```

## Alternative: Deploy using GitHub Actions

Create `.github/workflows/azure-deploy.yml`:

```yaml
name: Deploy to Azure

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v3
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.2'
        extensions: mongodb
        
    - name: Install dependencies
      run: composer install --no-dev --optimize-autoloader
      
    - name: Deploy to Azure Web App
      uses: azure/webapps-deploy@v2
      with:
        app-name: 'cinewave-app'
        publish-profile: ${{ secrets.AZURE_WEBAPP_PUBLISH_PROFILE }}
```

## Post Deployment

### 1. Verify Application
Visit: https://cinewave-app.azurewebsites.net

### 2. Check Logs
```bash
az webapp log tail --resource-group CineWave-RG --name cinewave-app
```

### 3. Seed Database (first time only)
```bash
# SSH into app
az webapp ssh --resource-group CineWave-RG --name cinewave-app

# Seed database
php artisan db:seed --force
```

## Troubleshooting

### If MongoDB extension is not installed:
1. SSH into the app
2. Run: `pecl install mongodb`
3. Add to php.ini: `echo "extension=mongodb.so" >> /usr/local/etc/php/conf.d/mongodb.ini`
4. Restart the app: `az webapp restart --resource-group CineWave-RG --name cinewave-app`

### If permissions are wrong:
```bash
az webapp ssh --resource-group CineWave-RG --name cinewave-app
chmod -R 755 /home/site/wwwroot/storage
chmod -R 755 /home/site/wwwroot/bootstrap/cache
```

### If routes are not working:
```bash
az webapp ssh --resource-group CineWave-RG --name cinewave-app
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

## Important Notes

1. **MongoDB Atlas**: Already configured and working
2. **Database**: No need to create new database, using existing MongoDB Atlas
3. **File Storage**: Using local file system (SESSION_DRIVER=file, CACHE_STORE=file)
4. **APP_KEY**: Already set, keep it secure
5. **Debug Mode**: Must be FALSE in production

## Routing Summary

All routes are working:
- Public: `/`, `/browse`, `/film/{slug}`
- Auth: `/login`, `/register`, `/verify-otp`, `/logout`
- User: `/profile`, `/watchlist`, `/watch/{slug}`
- Admin: `/admin/dashboard`, `/admin/users`, `/admin/films`, `/admin/categories`

## Database Connection

MongoDB Atlas is already configured:
- Host: cnw.0gsh98f.mongodb.net
- Database: cinewave
- Connection: mongodb+srv (secure)
- Collections: users, films, categories, watchlists, otps, activity_logs

All ready for production! 🚀
