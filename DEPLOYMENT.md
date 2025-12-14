# Azure App Service Deployment Guide

## Prerequisites

1. Azure Account with active subscription
2. Azure CLI installed (optional, for command line deployment)
3. GitHub repository with code pushed

## Step 1: Create Azure App Service

### Via Azure Portal:

1. Go to Azure Portal (https://portal.azure.com)
2. Create a new Resource Group (or use existing "WebOpet")
3. Create App Service:
   - Click "Create a resource"
   - Search for "Web App"
   - Click "Create"

### Configuration:

- **Resource Group:** WebOpet
- **Name:** cinewave2 (or your preferred name)
- **Publish:** Code
- **Runtime stack:** PHP 8.2
- **Operating System:** Linux
- **Region:** Southeast Asia (or nearest to your users)
- **Pricing:** Choose appropriate plan (recommend B1 or higher)

## Step 2: Configure Application Settings

Go to your App Service → Configuration → Application settings

Add the following environment variables:

```
APP_NAME=CineWave
APP_ENV=production
APP_KEY=base64:uQKpwDLbmTAj3nqlYTwugiRtv6dXJ/qo/aY3CbpP2EA=
APP_DEBUG=false
APP_URL=https://your-app-name.azurewebsites.net

DB_CONNECTION=mongodb
DB_URI=mongodb+srv://maskiryz23_db_user:biSdlM7bJKVBA8QP@cnw.0gsh98f.mongodb.net/?appName=CNW
DB_DATABASE=cinewave

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=maskiryz23@gmail.com
MAIL_PASSWORD=aeth lqgk huov pdsp
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=maskiryz23@gmail.com
MAIL_FROM_NAME=CineWave

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=public

LOG_CHANNEL=stack
LOG_LEVEL=info
```

## Step 3: Install MongoDB PHP Extension

### Via Azure Portal:

1. Go to App Service → Configuration → General settings
2. Scroll to "Startup Command"
3. Add: `apk add php82-mongodb && php artisan config:cache`

### Or add to composer.json:

The project already includes `jenssegers/mongodb` package.

## Step 4: Configure Deployment

### Option A: GitHub Actions (Recommended)

1. Go to App Service → Deployment Center
2. Choose GitHub as source
3. Authenticate with GitHub
4. Select repository: `Kiryzsuuu/CineWave2`
5. Select branch: `main`
6. Azure will automatically create GitHub workflow

### Option B: Local Git

1. Go to App Service → Deployment Center
2. Choose Local Git
3. Copy the Git URL
4. Add remote and push:

```bash
git remote add azure <your-azure-git-url>
git push azure main
```

### Option C: Manual Deployment via FTP

1. Go to App Service → Deployment Center
2. Get FTP credentials
3. Upload files via FTP client

## Step 5: Post-Deployment Configuration

### Set up Storage Permissions

SSH into your App Service or use Kudu console (https://your-app.scm.azurewebsites.net):

```bash
cd /home/site/wwwroot
chmod -R 755 storage
chmod -R 755 bootstrap/cache
php artisan storage:link
```

### Run Database Seeder

```bash
php artisan db:seed
```

This will create:
- Admin account (maskiryz23@gmail.com / admin123)
- Sample categories
- Sample films

### Clear and Cache Configuration

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Step 6: Configure Custom Domain (Optional)

1. Go to App Service → Custom domains
2. Add your custom domain
3. Validate domain ownership
4. Add SSL certificate (use App Service Managed Certificate - free)

## Step 7: Enable Application Insights (Recommended)

1. Go to App Service → Application Insights
2. Click "Turn on Application Insights"
3. This will help monitor performance and errors

## Step 8: Configure Scaling (Optional)

1. Go to App Service → Scale up (App Service plan)
   - Choose plan based on traffic needs
   
2. Go to App Service → Scale out (manual/auto)
   - Configure auto-scaling rules based on CPU/Memory

## Troubleshooting

### Issue: 500 Error after deployment

**Solution:**
1. Check Application Logs:
   - Go to App Service → Log stream
   - Check for errors

2. Enable detailed errors:
   - Set `APP_DEBUG=true` temporarily
   - Check error message
   - Set back to `false` after fixing

3. Verify environment variables are set correctly

### Issue: MongoDB connection failed

**Solution:**
1. Verify MongoDB connection string is correct
2. Ensure MongoDB Atlas IP whitelist includes Azure IPs (0.0.0.0/0 for testing)
3. Check if MongoDB PHP extension is installed:
   ```bash
   php -m | grep mongodb
   ```

### Issue: Email not sending

**Solution:**
1. Verify Gmail SMTP settings
2. Ensure App Password is correct (not regular password)
3. Check if Gmail account allows "Less secure apps" or use App Password

### Issue: Storage/Cache errors

**Solution:**
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## Monitoring & Maintenance

### View Logs

1. Via Portal:
   - Go to App Service → Log stream

2. Via Kudu:
   - Access https://your-app.scm.azurewebsites.net
   - Navigate to LogFiles

### Backup

1. Go to App Service → Backups
2. Configure automated backups
3. Set backup schedule and retention

### Update Application

**Method 1: GitHub (if using GitHub Actions)**
```bash
git add .
git commit -m "Update message"
git push origin main
```
Deployment will trigger automatically.

**Method 2: Manual**
```bash
git push azure main
```

## Performance Optimization

1. **Enable caching:**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Use CDN for static assets**
   - Configure Azure CDN
   - Update ASSET_URL in .env

3. **Enable compression:**
   - Already configured in web.config

4. **Optimize images:**
   - Use lazy loading (already implemented)
   - Compress images before upload

## Security Best Practices

1. **Always use HTTPS**
   - Enforce HTTPS in Azure App Service settings

2. **Keep APP_DEBUG=false in production**

3. **Rotate secrets regularly:**
   - APP_KEY
   - Database credentials
   - Email passwords

4. **Enable CORS only for trusted domains**

5. **Keep dependencies updated:**
   ```bash
   composer update
   ```

## Cost Optimization

1. **Choose right plan:**
   - B1 for development/testing
   - P1V2+ for production

2. **Use auto-scaling:**
   - Scale down during low traffic

3. **Monitor usage:**
   - Set up billing alerts

## Support

For issues, contact:
- Email: maskiryz23@gmail.com
- GitHub: https://github.com/Kiryzsuuu/CineWave2

## Useful Links

- Azure Portal: https://portal.azure.com
- Kudu Console: https://your-app.scm.azurewebsites.net
- Application URL: https://your-app.azurewebsites.net
- GitHub Repo: https://github.com/Kiryzsuuu/CineWave2
- MongoDB Atlas: https://cloud.mongodb.com

---

**Note:** Replace `your-app-name` with your actual App Service name throughout this guide.
