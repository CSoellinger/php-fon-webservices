# Upgrade Guide

## Modernization (2024)

This project has been modernized with new tooling and improved workflows. If you were contributing before this update, here's what changed:

### For Contributors

#### New Testing Framework: Pest

We've migrated from PHPUnit to Pest for a more expressive testing experience.

**Before:**
```php
public function testLoginAndLogout(): void
{
    $session = new SessionWs($this->fonCredential);
    $this->assertNotEmpty($session->getID());
}
```

**After:**
```php
test('login and logout', function () {
    $session = new SessionWs($this->fonCredential);
    expect($session->getID())->not->toBeEmpty();
});
```

Run tests with: `composer test`

#### New Code Formatting: PHP-CS-Fixer

We've replaced PHP_CodeSniffer with PHP-CS-Fixer for faster, more consistent formatting.

**Commands:**
```bash
composer format        # Auto-fix code style
composer format:check  # Check without fixing (for CI)
```

#### Simplified Composer Scripts

Old commands (no longer available):
- `composer dev:lint:syntax` → `composer lint`
- `composer dev:lint:style` → `composer format:check`
- `composer dev:analyze:phpstan` → `composer analyse:phpstan`
- `composer dev:analyze:psalm` → `composer analyse:psalm`
- `composer dev:test:unit` → `composer test`

New unified command:
```bash
composer check  # Runs lint + format check + static analysis
```

#### Git Hooks Changes

**Conventional Commits:** We've removed strict conventional commit enforcement. You can now use any commit message format.

**Re-install hooks:**
```bash
vendor/bin/captainhook install -f
```

The hooks now check:
- Composer validation (if composer.json changed)
- PHP syntax
- Code formatting
- Pre-push: All quality checks + tests

#### Multi-Version Testing

You can now test across all PHP versions locally:

```bash
# Install Podman and Podman Compose, then:
podman-compose build
podman-compose run --rm php82
bash bin/test-all-versions.sh
```

#### Static Analysis Updates

- **PHPStan**: Updated to ^1.12 (was ^1.4)
- **Psalm**: Updated to ^5.26 (was ^4.22)

Both tools are now run together with `composer analyse`

#### Automated Refactoring: Rector

We've added Rector for automated code improvements:

```bash
composer rector      # Preview refactoring suggestions
composer rector:fix  # Apply refactorings
```

### For Package Users

No breaking changes! The public API remains the same. This modernization only affects development workflows.

### Need Help?

- Check the updated [CONTRIBUTING.md](.github/CONTRIBUTING.md) for detailed guidelines
- Read the new Development section in [README.md](README.md)
- Open an issue if you have questions
