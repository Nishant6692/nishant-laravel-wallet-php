# Troubleshooting Guide

## Common Installation Issues

### Error: "Could not find a version of package nishant/wallet matching your minimum-stability (stable)"

**Problem**: Composer can't find a stable version of the package.

**Solutions**:

#### Solution 1: Create a Stable Version Tag (Recommended)

The package needs a stable Git tag (like `v1.0.0`) in your repository:

```bash
# In your package repository
git tag -a v1.0.0 -m "Version 1.0.0"
git push origin v1.0.0
```

Then update Packagist (either automatically via webhook or manually click "Update" on Packagist).

#### Solution 2: Install with Explicit Version Constraint

```bash
composer require nishant/wallet:^1.0
```

#### Solution 3: Allow Dev Stability (Temporary)

If you need to install before creating a stable tag:

```bash
composer require nishant/wallet --dev
```

Or add to your `composer.json`:

```json
{
    "require": {
        "nishant/wallet": "@dev"
    },
    "minimum-stability": "dev",
    "prefer-stable": true
}
```

#### Solution 4: Install Specific Branch

```bash
composer require nishant/wallet:dev-main
```

### Error: "Package nishant/wallet not found"

**Problem**: Package hasn't been published to Packagist yet.

**Solutions**:
1. Make sure you've submitted the package to Packagist
2. Wait a few minutes for Packagist to crawl your repository
3. Verify your repository is public
4. Check that `composer.json` is in the root of your repository

### Error: "Your requirements could not be resolved"

**Problem**: Version conflicts or missing dependencies.

**Solutions**:
1. Check PHP version: `php -v` (needs PHP 8.0+)
2. Check Laravel version: `php artisan --version` (needs Laravel 11+)
3. Update Composer: `composer self-update`
4. Clear Composer cache: `composer clear-cache`

### Migration Errors

**Problem**: Migrations fail to run.

**Solutions**:
1. Make sure you've published migrations:
   ```bash
   php artisan vendor:publish --tag=wallet-migrations
   ```
2. Check database connection
3. Verify migration files exist in `database/migrations/`

### Trait Not Found

**Problem**: `Class 'Nishant\Wallet\Traits\HasWallets' not found`

**Solutions**:
1. Run `composer dump-autoload`
2. Clear Laravel cache: `php artisan cache:clear`
3. Verify the package is installed: `composer show nishant/wallet`

## Getting Help

If you encounter other issues:

1. Check the [README.md](README.md) for detailed documentation
2. Review [PUBLISHING.md](PUBLISHING.md) for publishing-related issues
3. Open an issue on GitHub (if repository is public)
4. Verify all prerequisites are met (PHP 8.0+, Laravel 11+)

