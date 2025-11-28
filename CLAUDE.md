# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a PHP library for interacting with Austrian FinanzOnline (BMF) web services via SOAP. The library provides a type-safe interface to various tax administration services including session management, VAT ID validation, databox downloads, file uploads, and bank data transmission.

## Development Setup

The project uses ramsey/devtools for standardized development workflows. All development commands follow the `dev:*` prefix pattern.

### Common Commands

**Testing:**
- `composer dev:test:unit` - Run PHPUnit tests
- `composer dev:test:coverage:ci` - Run tests with code coverage report

**Code Quality:**
- `composer dev:lint:syntax` - Check PHP syntax with parallel-lint
- `composer dev:lint:style` - Check coding standards with PHP_CodeSniffer
- `composer dev:analyze:phpstan` - Run static analysis with PHPStan
- `composer dev:analyze:psalm` - Run static analysis with Psalm

**Running Examples:**
```bash
php -S localhost:8005 --docroot ./public
```
Then navigate to http://localhost:8005 to test example implementations.

## Architecture

### Core Service Structure

All webservice classes extend `SoapClient` and follow this pattern:

1. **SessionWs** - The foundational service that provides authentication. All other services depend on a SessionWs instance.
2. **Service-specific classes** - VatIdCheckWs, DataboxDownloadWs, FileUploadWs, BankDataTransmissionWs, QueryDataTransmissionWs

Each service:
- Accepts a SessionWs instance in its constructor
- Uses local WSDL files from `resources/wsdl/` when available, falls back to online WSDLs
- Handles automatic login if SessionWs is not already authenticated
- Returns typed response objects or throws exceptions on errors

### Directory Structure

```
src/
├── Authentication/FonCredential.php    # Credential container
├── SessionWs.php                       # Session management (required for all services)
├── VatIdCheckWs.php                    # VAT ID validation
├── DataboxDownloadWs.php               # Download from FinanzOnline databox
├── FileUploadWs.php                    # File uploads
├── BankDataTransmissionWs.php          # Bank data transmission
├── QueryDataTransmissionWs.php         # Query data transmission
├── Model/                              # Data transfer objects
├── Response/                           # Typed SOAP response classes
└── Util/                               # Helper utilities
```

### Authentication Flow

All services require FinanzOnline credentials:
1. Create `FonCredential` with: Teilnehmer-ID (teId), Teilnehmer-UID (teUid), Benutzer-ID (benId), Benutzer-PIN (benPin)
2. Initialize `SessionWs` with credentials
3. Call `login()` to authenticate (or let service auto-login)
4. Pass SessionWs instance to other service classes
5. SessionWs auto-logouts on destruction

### Response Handling

Services return typed objects from the `Model/` namespace (e.g., `VatIdCheckValidLevelTwo`, `DataboxDownloadListItem`). Errors are thrown as exceptions with error codes from the BMF API.

Common error codes across services:
- `0` - Success
- `-1` - Invalid/expired session
- `-2` - Maintenance
- `-3` - Technical error
- `-4` to `-8` - Service-specific authentication/authorization errors

### WSDL Management

Each service defines:
- `WSDL` constant - Online WSDL URL
- `WSDL_LOCAL` constant - Local WSDL file path in `resources/wsdl/`

The library prefers local WSDLs for performance and offline development.

## Testing

Tests are in `tests/` mirroring the `src/` structure. The project uses:
- PHPUnit for unit testing
- Snapshot testing (via spatie/phpunit-snapshot-assertions)
- Mock credentials from `.env` file for testing

Test configuration in `phpunit.xml.dist` with bootstrap at `tests/autoload.php`.

## Code Standards

- PHP 7.4+ with strict types (`declare(strict_types=1);`)
- PSR-4 autoloading (`CSoellinger\FonWebservices` namespace)
- Uses ramsey/conventional-commits for commit messages
- PHPStan and Psalm for static analysis
- PHP_CodeSniffer for style checking
- Git hooks via CaptainHook (configured in `captainhook.json`)

## Dependencies

- `ext-soap` - Required PHP SOAP extension
- `jasny/typecast` - Type casting utilities
- Development dependencies include symfony components for tooling

## Important Notes

- This library interacts with Austrian government tax services - handle credentials securely
- All credential validation happens in `FonCredential` constructor
- SessionWs automatically logs out when destroyed to prevent hanging sessions
- Services auto-login if SessionWs is not authenticated when making calls
- Error messages from BMF are in German
