# Laravel Package Plan

## Overview

This document outlines the plan for creating `csoellinger/laravel-fon-webservices`, a Laravel package that provides a convenient wrapper around the core `php-fon-webservices` library.

## Package Information

- **Name:** `csoellinger/laravel-fon-webservices`
- **Repository:** Separate repository (not monorepo)
- **Dependency:** `csoellinger/php-fon-webservices`
- **Minimum Laravel Version:** 9.x (PHP 8.0+)
- **Target Laravel Versions:** 9.x, 10.x, 11.x

## Architecture

### Service Provider

The package will include a `FonWebservicesServiceProvider` that:

1. Registers webservice classes as singletons in the container
2. Publishes configuration file
3. Provides artisan commands (optional)

```php
namespace CSoellinger\Laravel\FonWebservices;

use Illuminate\Support\ServiceProvider;
use CSoellinger\FonWebservices\SessionWs;
use CSoellinger\FonWebservices\VatIdCheckWs;

class FonWebservicesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/fon-webservices.php', 'fon-webservices'
        );

        $this->app->singleton(SessionWs::class, function ($app) {
            return new SessionWs(
                $app->make(FonCredential::class)
            );
        });

        $this->app->singleton(VatIdCheckWs::class, function ($app) {
            return new VatIdCheckWs(
                $app->make(SessionWs::class)
            );
        });

        // Register other webservices...
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/fon-webservices.php' => config_path('fon-webservices.php'),
            ], 'config');
        }
    }
}
```

### Configuration File

`config/fon-webservices.php`:

```php
return [
    /*
    |--------------------------------------------------------------------------
    | FinanzOnline Credentials
    |--------------------------------------------------------------------------
    |
    | Your FinanzOnline web service credentials. These should be stored
    | in your .env file for security.
    |
    */

    'credentials' => [
        'te_id' => env('FON_TE_ID'),
        'te_uid' => env('FON_TE_UID'),
        'ben_id' => env('FON_BEN_ID'),
        'ben_pin' => env('FON_BEN_PIN'),
    ],

    /*
    |--------------------------------------------------------------------------
    | SOAP Options
    |--------------------------------------------------------------------------
    |
    | Additional SOAP client options. See PHP SOAP documentation.
    |
    */

    'soap_options' => [
        // 'trace' => true,
        // 'exceptions' => true,
    ],
];
```

### Facades

Provide convenient facades for common operations:

```php
namespace CSoellinger\Laravel\FonWebservices\Facades;

use Illuminate\Support\Facades\Facade;

class FonVatIdCheck extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \CSoellinger\FonWebservices\VatIdCheckWs::class;
    }
}
```

### Usage Examples

#### Basic Usage (Dependency Injection)

```php
use CSoellinger\FonWebservices\VatIdCheckWs;

class InvoiceController extends Controller
{
    public function __construct(
        private VatIdCheckWs $vatIdCheck
    ) {}

    public function store(Request $request)
    {
        $result = $this->vatIdCheck->check($request->vat_id, 2);

        if ($result->valid) {
            // VAT ID is valid
        }
    }
}
```

#### Facade Usage

```php
use CSoellinger\Laravel\FonWebservices\Facades\FonVatIdCheck;

$result = FonVatIdCheck::check('ATU36975500', 2);
```

#### Artisan Commands (Optional)

```bash
php artisan fon:check-vat ATU36975500
php artisan fon:list-databox
```

## Environment Variables

Add to `.env`:

```env
FON_TE_ID=your_teilnehmer_id
FON_TE_UID=your_teilnehmer_uid
FON_BEN_ID=your_benutzer_id
FON_BEN_PIN=your_benutzer_pin
```

## Testing Strategy

- Use Laravel's testing utilities
- Mock FinanzOnline API responses
- Provide test helpers for common scenarios

```php
use CSoellinger\Laravel\FonWebservices\Testing\FonWebservicesFake;

FonWebservicesFake::mockVatIdCheck([
    'ATU36975500' => ['valid' => true, 'name' => 'Test Company'],
]);
```

## Package Structure

```
laravel-fon-webservices/
├── config/
│   └── fon-webservices.php
├── src/
│   ├── Console/
│   │   ├── CheckVatCommand.php
│   │   └── ListDataboxCommand.php
│   ├── Facades/
│   │   ├── FonSession.php
│   │   ├── FonVatIdCheck.php
│   │   └── FonDataboxDownload.php
│   ├── Testing/
│   │   └── FonWebservicesFake.php
│   └── FonWebservicesServiceProvider.php
├── tests/
│   ├── Feature/
│   └── Unit/
├── composer.json
└── README.md
```

## Installation (Future)

```bash
composer require csoellinger/laravel-fon-webservices
```

```bash
php artisan vendor:publish --provider="CSoellinger\Laravel\FonWebservices\FonWebservicesServiceProvider"
```

## Development Timeline

1. **Phase 1:** Complete core library modernization ✓ (current)
2. **Phase 2:** Create Laravel package repository
3. **Phase 3:** Implement service provider and facades
4. **Phase 4:** Add artisan commands
5. **Phase 5:** Create comprehensive tests
6. **Phase 6:** Documentation and examples
7. **Phase 7:** Publish to Packagist

## Benefits of Separate Package

- **Separation of Concerns:** Core library remains framework-agnostic
- **Version Independence:** Laravel package can have its own release cycle
- **Reduced Dependencies:** Core library doesn't depend on Laravel
- **Flexibility:** Users can choose raw library or Laravel integration
- **Testing:** Easier to test Laravel-specific features in isolation

## Notes

- Keep Laravel package thin - business logic stays in core library
- Follow Laravel package development best practices
- Support latest 3 Laravel LTS/major versions
- Provide migration guides for Laravel version upgrades
- Consider adding event broadcasting for long-running operations
