# Azure Deployment Package Script
# This script creates a deployment package for Azure App Service

Write-Host "=== Creating Azure Deployment Package ===" -ForegroundColor Green

# Install dependencies
Write-Host "`nInstalling Composer dependencies..." -ForegroundColor Yellow
composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-req=ext-mongodb

if ($LASTEXITCODE -ne 0) {
    Write-Host "Failed to install dependencies!" -ForegroundColor Red
    exit 1
}

# Create deployment package
Write-Host "`nCreating deployment ZIP..." -ForegroundColor Yellow
$deployFiles = @(
    "app",
    "bootstrap",
    "config",
    "database",
    "public",
    "resources",
    "routes",
    "storage",
    "vendor",
    "artisan",
    "composer.json",
    "composer.lock",
    "web.config",
    "startup-php.sh"
)

# Create temp directory
$tempDir = "azure-deploy-temp"
if (Test-Path $tempDir) {
    Remove-Item -Recurse -Force $tempDir
}
New-Item -ItemType Directory -Path $tempDir | Out-Null

# Copy files
Write-Host "Copying files..." -ForegroundColor Yellow
foreach ($file in $deployFiles) {
    if (Test-Path $file) {
        Copy-Item -Path $file -Destination $tempDir -Recurse -Force
        Write-Host "  ✓ $file" -ForegroundColor Gray
    }
}

# Create ZIP
$zipPath = "cinewave-deploy.zip"
if (Test-Path $zipPath) {
    Remove-Item -Force $zipPath
}

Write-Host "`nCompressing to ZIP..." -ForegroundColor Yellow
Compress-Archive -Path "$tempDir\*" -DestinationPath $zipPath -CompressionLevel Optimal

# Cleanup
Remove-Item -Recurse -Force $tempDir

Write-Host "`n=== Package Ready ===" -ForegroundColor Green
Write-Host "File: $zipPath" -ForegroundColor Cyan
Write-Host "Size: $([math]::Round((Get-Item $zipPath).Length / 1MB, 2)) MB" -ForegroundColor Cyan

Write-Host "`nTo deploy, run:" -ForegroundColor Yellow
Write-Host "  az webapp deploy --resource-group WebOpet --name CineWave --src-path $zipPath --type zip" -ForegroundColor White
