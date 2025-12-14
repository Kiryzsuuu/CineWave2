# Quick Start Guide

## Local Development Setup

### 1. Prerequisites Check

Ensure you have the following installed:
- PHP 8.2 or higher
- Composer
- Git
- MongoDB (local) or MongoDB Atlas account
- Web server (Apache/Nginx) or use `php artisan serve`

### 2. Clone Repository

```bash
git clone https://github.com/Kiryzsuuu/CineWave2.git
cd CineWave2
```

### 3. Install Dependencies

```bash
composer install --ignore-platform-req=ext-mongodb
```

**Note:** If you don't have MongoDB extension installed, use the `--ignore-platform-req=ext-mongodb` flag. The app will still work but you'll need to install the extension for production use.

### 4. Configure Environment

```bash
cp .env.example .env
```

Edit `.env` file with your configuration:

```env
APP_NAME=CineWave
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# MongoDB Configuration
DB_CONNECTION=mongodb
DB_URI=<your-mongodb-uri>
DB_DATABASE=cinewave

# Email Configuration (for OTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=<smtp-username>
MAIL_PASSWORD=<smtp-password>
MAIL_ENCRYPTION=tls

# Session & Cache (use 'file' for local development)
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Install MongoDB PHP Extension (Required for production)

#### Windows (XAMPP/WAMP):

1. Download MongoDB extension:
   - Visit: https://pecl.php.net/package/mongodb
   - Download DLL for your PHP version (8.2 TS x64)

2. Install extension:
   - Copy `php_mongodb.dll` to `C:\xampp\php\ext\` (or your PHP ext folder)
   - Edit `php.ini` (both in php folder and apache/bin folder)
   - Add line: `extension=mongodb`
   - Restart Apache

3. Verify installation:
   ```bash
   php -m | grep mongodb
   ```

#### Linux:

```bash
sudo pecl install mongodb
echo "extension=mongodb.so" | sudo tee -a /etc/php/8.2/cli/php.ini
echo "extension=mongodb.so" | sudo tee -a /etc/php/8.2/apache2/php.ini
sudo systemctl restart apache2
```

#### macOS:

```bash
pecl install mongodb
echo "extension=mongodb.so" >> /usr/local/etc/php/8.2/php.ini
brew services restart php
```

### 7. Create Storage Link

```bash
php artisan storage:link
```

### 8. Seed Database

```bash
php artisan db:seed
```

This creates:
- **Admin account:** optional (set `SEED_ADMIN_EMAIL` and `SEED_ADMIN_PASSWORD` before running seeder)
- Sample categories (Action, Comedy, Drama, etc.)
- Sample films

### 9. Start Development Server

```bash
php artisan serve
```

Access the application at: http://localhost:8000

### 10. Access Points

- **Homepage:** http://localhost:8000
- **Login:** http://localhost:8000/login
- **Register:** http://localhost:8000/register
- **Admin Dashboard:** http://localhost:8000/admin/dashboard (login as admin first)

## Default Accounts

### Admin Account
No default admin account is seeded automatically.

To seed an admin intentionally (recommended for local/dev only), set:
- `SEED_ADMIN_EMAIL`
- `SEED_ADMIN_PASSWORD`
Then run:
```bash
php artisan db:seed
```

## Common Issues & Solutions

### Issue: "Class 'MongoDB\Driver\Manager' not found"

**Solution:** MongoDB PHP extension is not installed. Either:
1. Install the extension (see step 6 above)
2. Or use `composer install --ignore-platform-req=ext-mongodb` for development

### Issue: "SQLSTATE connection refused"

**Solution:** You're trying to use SQL database instead of MongoDB.
1. Check `.env` file: `DB_CONNECTION=mongodb`
2. Clear config: `php artisan config:clear`
3. Restart server

### Issue: "Base table or view not found"

**Solution:** This error shouldn't occur with MongoDB. If you see it:
1. Ensure MongoDB connection string is correct in `.env`
2. Check if MongoDB Atlas IP whitelist allows your IP
3. Verify database name exists

### Issue: Email not sending during OTP verification

**Solution:**
1. Check Gmail SMTP credentials in `.env`
2. Ensure you're using App Password (not regular password)
3. Enable "Less secure app access" in Gmail settings
4. Or temporarily set `MAIL_MAILER=log` to test without email

### Issue: Session errors

**Solution:**
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
php artisan cache:clear
```

## Development Workflow

### Adding New Films

1. Login as admin
2. Go to Admin Dashboard → Films → Create New
3. Fill in film details
4. Upload poster/backdrop URLs
5. Save

### Managing Users

1. Login as admin
2. Go to Admin Dashboard → Users
3. View, edit, or delete users
4. Create new users

### Managing Categories

1. Login as admin
2. Go to Admin Dashboard → Categories
3. Create, edit, or delete categories

## Testing Email OTP

### Option 1: Use Real SMTP
- Configure Gmail SMTP (already done in `.env`)
- Register with real email
- Check inbox for OTP

### Option 2: Use Log Driver (for testing)
1. Set `MAIL_MAILER=log` in `.env`
2. Clear config: `php artisan config:clear`
3. Register user
4. Check OTP in `storage/logs/laravel.log`

## File Structure Overview

```
CineWave2/
├── app/
│   ├── Http/Controllers/
│   │   ├── Auth/              # Authentication
│   │   │   └── AuthController.php
│   │   ├── Admin/             # Admin panel
│   │   │   ├── DashboardController.php
│   │   │   ├── UserController.php
│   │   │   ├── FilmController.php
│   │   │   └── CategoryController.php
│   │   └── HomeController.php # Public pages
│   ├── Models/
│   │   ├── User.php
│   │   ├── Film.php
│   │   ├── Category.php
│   │   ├── Otp.php
│   │   └── ActivityLog.php
│   └── Middleware/
│       └── AdminMiddleware.php
├── resources/views/
│   ├── layouts/
│   │   └── app.blade.php      # Main layout
│   ├── auth/                  # Auth pages
│   ├── admin/                 # Admin pages
│   ├── film/                  # Film pages
│   └── emails/                # Email templates
├── routes/
│   └── web.php                # All routes
├── .env                       # Environment config
└── web.config                 # Azure/IIS config
```

## Next Steps

1. **Customize Design:** Edit views in `resources/views/`
2. **Add More Films:** Use admin panel
3. **Configure Payment:** Integrate payment gateway for subscriptions
4. **Deploy to Azure:** Follow `DEPLOYMENT.md`

## Useful Commands

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# View routes
php artisan route:list

# Check MongoDB connection
php artisan tinker
> DB::connection()->getMongoDB()

# Reset database
php artisan db:seed --force
```

## Support

- **Email:** maskiryz23@gmail.com
- **GitHub Issues:** https://github.com/Kiryzsuuu/CineWave2/issues
- **Documentation:** Check README.md and DEPLOYMENT.md

## License

Private and Proprietary

---

Happy coding! 🎬🍿
