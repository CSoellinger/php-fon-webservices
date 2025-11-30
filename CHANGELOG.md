# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2025-11-30

### Added
- **PHP 8.1+ Enum Support**: Added 9 backed enum classes for type-safe API parameters
  - `VatIdCheckLevel`: VAT ID check levels (SimpleCheck, FullCheck)
  - `BankDataType`: Bank data transmission types
  - `FileUploadType`: File upload submission types (36 types)
  - `DataboxType`: Databox filter types (16 types)
  - `QueryDataType`: Query transmission types
  - `VatIdReturnCode`: All VAT ID check return codes
  - `Gender`: Employee gender types (German values)
  - `SocialStatus`: Employee social status types (German values)
  - `EmploymentType`: Employment types (German values)

- **PHP 8.1+ Constructor Property Promotion**: Applied to all webservice classes and `FonCredential`
  - Reduces boilerplate code by ~45 lines
  - Cleaner, more modern syntax
  - All constructor parameters now use property promotion

- **PHP 8.1+ Readonly Properties**: Added to constructor-promoted properties
  - `SessionWs::$credential`
  - All webservice `$sessionWs` properties
  - Prevents accidental mutation after initialization

- **Union Return Types**: Added explicit union types to public methods
  - `VatIdCheckWs::check()`: Returns `VatIdCheckInvalid|VatIdCheckValidLevelOne|VatIdCheckValidLevelTwo`
  - `SessionWs::login()` and `logout()`: Return `static` for better inheritance support

- **PHP 8.4 Support**: Added support for PHP 8.4 in addition to 8.1, 8.2, and 8.3

### Changed
- **BREAKING**: Dropped support for PHP 7.4 and 8.0
  - Minimum PHP version is now 8.1
  - Composer constraint: `^8.1 || ^8.2 || ^8.3 || ^8.4`

- **Pest Testing Framework**: Upgraded from v1.x to v2.x/v3.x
  - Better coverage reporting with PCOV
  - Improved test syntax and features
  - Simplified coverage scripts

- **Dependency Updates**: Updated all Symfony components to support v7.0
  - `symfony/console`: `^5.4 || ^6.4 || ^7.0`
  - `symfony/dotenv`: `^5.4 || ^6.4 || ^7.0`
  - `symfony/filesystem`: `^5.4 || ^6.4 || ^7.0`
  - `symfony/finder`: `^5.4 || ^6.4 || ^7.0`
  - `symfony/process`: `^5.4 || ^6.4 || ^7.0`
  - `symfony/var-dumper`: `^5.4 || ^6.4 || ^7.0`

- **Enum Integration**: All webservice type parameters now accept enums
  - `VatIdCheckWs::check()`: Accepts `VatIdCheckLevel` enum (int backward compatible)
  - `BankDataTransmissionWs::upload()`: Accepts `BankDataType` enum (string backward compatible)
  - `FileUploadWs::upload()`: Accepts `FileUploadType` enum (string backward compatible)
  - `DataboxDownloadWs::get()`: Accepts `DataboxType` enum (string backward compatible)
  - `QueryDataTransmissionWs::query()`: Accepts `QueryDataType` enum (string backward compatible)

- **Deprecated Constants**: Old type constants are now deprecated
  - `VatIdCheckWs::LEVEL_SIMPLE_CHECK` → Use `VatIdCheckLevel::SimpleCheck`
  - `VatIdCheckWs::LEVEL_FULL_CHECK` → Use `VatIdCheckLevel::FullCheck`

### Removed
- **PHP 7.4 and 8.0 Support**: Removed completely
  - Docker images for PHP 7.4 and 8.0 removed
  - CI/CD test matrix updated to only test 8.1-8.4
  - `symfony/polyfill-php80` dependency removed

### Infrastructure
- **Docker/Podman**: Updated infrastructure for PHP 8.1-8.4
  - Removed PHP 7.4 and 8.0 Docker images
  - Added PCOV extension to all PHP version images
  - Updated to latest Alpine base images

- **GitHub Actions**: Updated CI pipeline
  - Test matrix: PHP 8.1, 8.2, 8.3, 8.4
  - Coverage testing on PHP 8.2 with PCOV
  - Added missing coverage test steps

## [1.0.0] - Previous Release

### Added
- Initial stable release
- Support for PHP 7.4, 8.0, 8.1
- Core webservice implementations:
  - Session management (login/logout)
  - VAT ID validation
  - Bank data transmission
  - File upload
  - Databox download
  - Query data transmission

[2.0.0]: https://github.com/CSoellinger/php-fon-webservices/compare/v1.0.0...v2.0.0
[1.0.0]: https://github.com/CSoellinger/php-fon-webservices/releases/tag/v1.0.0
