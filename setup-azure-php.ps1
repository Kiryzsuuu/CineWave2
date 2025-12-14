param(
    [string]$MongoUri = $env:DB_URI,
    [string]$AppKey = $env:APP_KEY,
    [string]$AppUrl = "https://cinewave.azurewebsites.net"
)

# Setup Azure App Service for PHP Native Deployment
Write-Host "=== Configuring Azure App Service for PHP ===" -ForegroundColor Green

$resourceGroup = "WebOpet"
$appName = "CineWave"

if ([string]::IsNullOrWhiteSpace($AppKey)) {
    throw "APP_KEY is required. Provide -AppKey or set env var APP_KEY (example: base64:...)."
}

if ([string]::IsNullOrWhiteSpace($MongoUri)) {
    throw "DB_URI is required. Provide -MongoUri or set env var DB_URI (example: mongodb+srv://...)."
}

# Remove Docker settings
Write-Host "`nRemoving Docker registry settings..." -ForegroundColor Yellow
az webapp config appsettings delete --resource-group $resourceGroup --name $appName --setting-names DOCKER_REGISTRY_SERVER_URL DOCKER_REGISTRY_SERVER_USERNAME DOCKER_REGISTRY_SERVER_PASSWORD DOCKER_CUSTOM_IMAGE_NAME 2>$null

# Set PHP app settings one by one
Write-Host "`nSetting application configuration..." -ForegroundColor Yellow

$settings = @{
    "APP_NAME" = "CineWave"
    "APP_ENV" = "production"
    "APP_KEY" = $AppKey
    "APP_DEBUG" = "false"
    "APP_URL" = $AppUrl
    "DB_CONNECTION" = "mongodb"
    "DB_DATABASE" = "cinewave"
    "SESSION_DRIVER" = "file"
    "CACHE_STORE" = "file"
    "WEBSITES_ENABLE_APP_SERVICE_STORAGE" = "true"
    "SCM_DO_BUILD_DURING_DEPLOYMENT" = "false"
}

foreach ($key in $settings.Keys) {
    Write-Host "  Setting $key..." -NoNewline -ForegroundColor Gray
    az webapp config appsettings set --resource-group $resourceGroup --name $appName --settings "$key=$($settings[$key])" --output none
    if ($LASTEXITCODE -eq 0) {
        Write-Host " ✓" -ForegroundColor Green
    } else {
        Write-Host " ✗" -ForegroundColor Red
    }
}

# Set MongoDB URI separately (complex string)
Write-Host "  Setting DB_URI..." -NoNewline -ForegroundColor Gray
az webapp config appsettings set --resource-group $resourceGroup --name $appName --settings "DB_URI=$MongoUri" --output none
if ($LASTEXITCODE -eq 0) {
    Write-Host " ✓" -ForegroundColor Green
} else {
    Write-Host " ✗" -ForegroundColor Red
}

# Set startup command
Write-Host "`nSetting startup command..." -ForegroundColor Yellow
az webapp config set --resource-group $resourceGroup --name $appName --startup-file "/home/site/wwwroot/startup-php.sh" --output none

Write-Host "`n=== Configuration Complete ===" -ForegroundColor Green
Write-Host "`nNext steps:" -ForegroundColor Yellow
Write-Host "1. Run: .\create-deploy-package.ps1" -ForegroundColor White
Write-Host "2. Deploy the ZIP file manually via:" -ForegroundColor White
Write-Host "   - Azure Portal > App Service > Deployment Center > ZIP Deploy" -ForegroundColor Cyan
Write-Host "   OR" -ForegroundColor White
Write-Host "   - az webapp deploy --resource-group WebOpet --name CineWave --src-path cinewave-deploy.zip --type zip" -ForegroundColor Cyan
