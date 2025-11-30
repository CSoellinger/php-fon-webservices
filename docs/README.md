# API Documentation

This directory contains the auto-generated API documentation for the PHP FinanzOnline Webservices library.

## Viewing Documentation Locally

To view the documentation locally:

```bash
# Generate the documentation
composer docs

# Serve the documentation
composer docs:serve
```

Then open http://localhost:8000 in your browser.

## Generating Documentation

The documentation is generated using [Doctum](https://github.com/code-lts/doctum) from PHPDoc comments in the source code.

```bash
composer docs
```

This will:
- Parse all PHP files in `src/`
- Extract PHPDoc comments
- Generate HTML documentation in `docs/api/`

## Configuration

The documentation configuration is in `doctum.php` at the project root. You can customize:
- Title
- Build directory
- GitHub repository link
- Default opened level

## GitHub Pages

The documentation is automatically deployed to GitHub Pages when code is pushed to the `main` branch.

**Workflow**: `.github/workflows/docs.yml`

### Enabling GitHub Pages

1. Go to repository **Settings** → **Pages**
2. Under "Build and deployment":
   - Source: **GitHub Actions**
3. Save

Your documentation will be available at:
```
https://csoellinger.github.io/php-fon-webservices/
```

## Manual Deployment

If you want to manually commit the generated docs:

```bash
# Generate documentation
composer docs

# Add to git (docs/api/ is in .gitignore by default)
git add -f docs/api

# Commit and push
git commit -m "docs: Update API documentation"
git push

# Then enable Pages with "Deploy from branch" and select "/docs" folder
```

## Updating Documentation

The documentation is automatically updated on every push to `main` that modifies:
- `src/**` - Source code changes
- `doctum.php` - Configuration changes
- `.github/workflows/docs.yml` - Workflow changes

To manually trigger documentation rebuild, go to **Actions** → **Documentation** → **Run workflow**.
