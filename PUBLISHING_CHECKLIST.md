# Packagist Publishing Checklist

Use this checklist before publishing your package to Packagist.

## Pre-Publishing Checklist

### ✅ Git Repository
- [ ] Initialize git repository (`git init`)
- [ ] Create a public repository on GitHub/GitLab/Bitbucket
- [ ] Push all code to the repository
- [ ] Create an initial release tag (e.g., `v1.0.0`)

### ✅ composer.json
- [ ] Update author email in `composer.json`
- [ ] Add repository URL (homepage, support links) - optional but recommended
- [ ] Run `composer validate` - should show "is valid"
- [ ] Verify package name follows convention: `vendor/package-name`

### ✅ Files
- [ ] README.md is complete and clear
- [ ] LICENSE file exists and matches composer.json license
- [ ] .gitignore is properly configured
- [ ] All source files are committed

### ✅ Testing
- [ ] Test package installation locally using path repository
- [ ] Verify all features work as expected
- [ ] Check that migrations run successfully

## Publishing Steps

1. [ ] Log in to [packagist.org](https://packagist.org/)
2. [ ] Click "Submit" in the menu
3. [ ] Enter your repository URL
4. [ ] Click "Check" to validate
5. [ ] Click "Submit" to publish
6. [ ] Wait for Packagist to crawl (few minutes)
7. [ ] Verify package appears on Packagist

## Post-Publishing

- [ ] Test installation: `composer require nishant/wallet`
- [ ] Set up GitHub webhook for automatic updates
- [ ] Update README badges (they'll work after publishing)
- [ ] Share your package! 🎉

## Quick Commands

```bash
# Validate composer.json
composer validate

# Create git tag for version
git tag -a v1.0.0 -m "Version 1.0.0"
git push origin v1.0.0

# Test local installation
composer require nishant/wallet
```

