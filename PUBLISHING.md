# Publishing to Packagist

This guide will help you publish the Nishant Wallet package to [Packagist.org](https://packagist.org/).

## Prerequisites

1. **Git Repository**: Your package must be in a public Git repository (GitHub, GitLab, Bitbucket, etc.)
2. **Packagist Account**: Create an account at [packagist.org](https://packagist.org/)
3. **Valid composer.json**: The package must have a valid `composer.json` file

## Step-by-Step Publishing Guide

### Step 1: Prepare Your Git Repository

1. **Initialize Git** (if not already done):
   ```bash
   git init
   git add .
   git commit -m "Initial commit: Nishant Wallet package"
   ```

2. **Create a GitHub/GitLab Repository**:
   - Go to GitHub.com or GitLab.com
   - Create a new public repository (e.g., `nishant-wallet-laravel`)
   - **Important**: Make it PUBLIC (Packagist requires public repositories for free accounts)

3. **Push Your Code**:
   ```bash
   git remote add origin https://github.com/your-username/nishant-wallet-laravel.git
   git branch -M main
   git push -u origin main
   ```

4. **Create a Release Tag** (recommended):
   ```bash
   git tag -a v1.0.0 -m "Version 1.0.0"
   git push origin v1.0.0
   ```

### Step 2: Update composer.json

Before publishing, make sure to:

1. **Update Author Information**:
   Edit `composer.json` and update the author email:
   ```json
   "authors": [
       {
           "name": "Your Name",
           "email": "your-actual-email@example.com"
       }
   ]
   ```

2. **Add Repository URL** (optional but recommended):
   ```json
   "homepage": "https://github.com/your-username/nishant-wallet-laravel",
   "support": {
       "issues": "https://github.com/your-username/nishant-wallet-laravel/issues",
       "source": "https://github.com/your-username/nishant-wallet-laravel"
   }
   ```

### Step 3: Validate composer.json

Run this command to validate your `composer.json`:

```bash
composer validate
```

You should see: `./composer.json is valid`

### Step 4: Submit to Packagist

1. **Log in to Packagist**:
   - Go to [packagist.org](https://packagist.org/)
   - Click "Sign in" or "Create account"
   - You can use GitHub to log in

2. **Submit Your Package**:
   - Click "Submit" in the top menu
   - Enter your repository URL (e.g., `https://github.com/your-username/nishant-wallet-laravel`)
   - Click "Check" to validate
   - Click "Submit" to publish

3. **Wait for Crawling**:
   - Packagist will automatically crawl your repository
   - This usually takes a few minutes
   - You'll receive an email when it's done

### Step 5: Verify Installation

Once published, test the installation:

```bash
composer require nishant/wallet
```

The package should be installed in `vendor/nishant/wallet/`

## Updating Your Package

### Automatic Updates

Packagist automatically checks for updates. However, you can:

1. **Enable GitHub Hook** (Recommended):
   - Go to your Packagist package page
   - Click "Update" button
   - Copy the webhook URL
   - Go to your GitHub repository → Settings → Webhooks
   - Add the Packagist webhook URL
   - Now every push will automatically update Packagist

2. **Manual Update**:
   - Go to your package page on Packagist
   - Click the "Update" button

### Creating New Versions

1. **Create a Git Tag**:
   ```bash
   git tag -a v1.0.1 -m "Version 1.0.1 - Bug fixes"
   git push origin v1.0.1
   ```

2. **Update Packagist**:
   - Either wait for automatic update (if webhook is set)
   - Or manually click "Update" on Packagist

## Package Naming Convention

- **Vendor Name**: `nishant` (your username or organization)
- **Package Name**: `wallet` (descriptive name)
- **Full Name**: `nishant/wallet`

## Important Notes

1. **Repository Must Be Public**: Free Packagist accounts require public repositories
2. **Version Tags**: Use semantic versioning (v1.0.0, v1.0.1, v2.0.0)
3. **composer.json Must Be Valid**: Always run `composer validate` before pushing
4. **README.md**: A good README helps users understand your package
5. **License**: Make sure LICENSE file matches the license in composer.json

## Troubleshooting

### Package Not Found After Submission
- Wait a few minutes for Packagist to crawl
- Check that your repository is public
- Verify composer.json is in the root directory

### Validation Errors
- Run `composer validate` to see specific errors
- Check JSON syntax
- Ensure all required fields are present

### Updates Not Showing
- Check if webhook is configured correctly
- Manually trigger update on Packagist
- Verify git tags are pushed

## Next Steps After Publishing

1. **Add Badge to README**:
   ```markdown
   [![Latest Version on Packagist](https://img.shields.io/packagist/v/nishant/wallet.svg?style=flat-square)](https://packagist.org/packages/nishant/wallet)
   [![Total Downloads](https://img.shields.io/packagist/dt/nishant/wallet.svg?style=flat-square)](https://packagist.org/packages/nishant/wallet)
   ```

2. **Update README Installation**:
   Update the README to show the Packagist installation method as primary

3. **Documentation**: Ensure all documentation is complete

4. **Testing**: Test the package installation in a fresh Laravel project

## Resources

- [Packagist Documentation](https://packagist.org/about)
- [Composer Documentation](https://getcomposer.org/doc/)
- [Semantic Versioning](https://semver.org/)

