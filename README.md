# csoellinger/php-fon-webservices

**PHP library to handle (BMF)FinanzOnline webservices.**

[![Source Code][source-badge]][source]
[![Download Package][packagist-badge]][packagist]
[![PHP Programming Language][php-badge]][php]
[![Read License][license-badge]][license]
[![Package downloads on Packagist][downloads-badge]][downloads]
[![Build status][build-badge]][build]
[![Psalm Type Coverage][psalm-badge]][psalm]
[![Codecov Code Coverage][codecov-badge]][codecov]
<!-- [![Chat with the maintainers][chat-badge]][chat] -->

[source]: https://github.com/csoellinger/php-fon-webservices
[source-badge]: https://img.shields.io/badge/source-csoellinger/php--fon--webservices-blue.svg?style=flat-square
[packagist]: https://packagist.org/packages/csoellinger/php-fon-webservices
[packagist-badge]: https://img.shields.io/packagist/v/csoellinger/php-fon-webservices.svg?style=flat-square&label=release
[php]: https://php.net
[php-badge]: https://img.shields.io/packagist/php-v/csoellinger/php-fon-webservices.svg?style=flat-square&colorB=%238892BF
[license]: https://github.com/csoellinger/php-fon-webservices/blob/master/UNLICENSE
[license-badge]: https://img.shields.io/packagist/l/csoellinger/php-fon-webservices.svg?style=flat-square&colorB=darkcyan
[downloads]: https://packagist.org/packages/csoellinger/php-fon-webservices/stats
[downloads-badge]: https://img.shields.io/packagist/dt/csoellinger/php-fon-webservices.svg?style=flat-square&colorB=darkmagenta
[build]: https://github.com/csoellinger/php-fon-webservices/actions?query=workflow%3ACI
[build-badge]: https://img.shields.io/github/workflow/status/csoellinger/php-fon-webservices/CI?label=CI&logo=github&style=flat-square
[codecov]: https://codecov.io/gh/csoellinger/php-fon-webservices
[codecov-badge]: https://img.shields.io/codecov/c/gh/csoellinger/php-fon-webservices?label=codecov&logo=codecov&style=flat-square
[psalm]: https://shepherd.dev/github/csoellinger/php-fon-webservices
[psalm-badge]: https://img.shields.io/endpoint?style=flat-square&url=https%3A%2F%2Fshepherd.dev%2Fgithub%2Fcsoellinger%2Fphp-fon-webservices%2Fcoverage
[chat]: https://phpc.chat/channel/csoellinger
[chat-badge]: https://img.shields.io/badge/phpc.chat-%23csoellinger-darkslateblue?style=flat-square

- [About](#about)
- [Installation](#installation)
- [Usage](#usage)
  - [Session Webservice](#session-webservice)
  - [VatID Check Webservice](#vatid-check-webservice)
  - [Databox Download Webservice](#databox-download-webservice)
- [Example(s)](#examples)
- [Documentation](#documentation)
- [Contributing](#contributing)
- [Copyright and License](#copyright-and-license)

---

## About

Supported webservices for now:
- Session
- VatID check
- Databox download
- File upload
- Bank data transmission

To be done:
- Query data transmission
- Record keeping
- Cash register

## Installation

Install this package as a dependency using [Composer](https://getcomposer.org).

``` bash
composer require csoellinger/php-fon-webservices
```

## Usage

---

### Session Webservice

This webservice is needed for all other webservices but can also be used standalone (e.g. for using it with a service which is not supported by this library at the moment). Before you can use it you need to set up a credential class with all necessary informations.

```php
<?php

$tId = 'abcd45678'; // FinanzOnline Teilnehmer-ID
$tUid = 'abcd45678'; // FinanzOnline Teilnehmer-UID (if available, otherwise leave blank)
$benId = 'abcd45678'; // FinanzOnline Benutzer-ID
$benPin = 'abcd45678'; // FinanzOnline Benutzer-PIN

$credential = new FonCredential($tId, $tUid, $benId, $benPin);

// Initialize
$sessionWs = new SessionWs($credential);

// Login
$sessionWs->login();

// Get session id
$sessionId = $sessionWs->getID();

// optional: logout. Not really needed cause you will be logged off at class destruct.
$sessionWs->logout();
```

---

### VatID Check Webservice

Check a vat id (value added tax identification) for validity at two levels. First only return valid or not valid,
second also return the company name and address if available.

```php
// Initialize with previous created session webservice

/** @var SessionWs $sessionWs */
$checkVatIdWs = new VatIdCheckWs($sessionWs);

// Check at level one only returns valid
var_dump($checkVatIdWs->check('ATU36975500'));
/**
 * CSoellinger\FonWebservices\Result\VatIdCheck\ValidLevelOne
 *   valid => (bool) true
 */

// At level two you also get name and address of the organisation if available
var_dump($checkVatIdWs->check('ATU36975500', 2));
/**
 * CSoellinger\FonWebservices\Result\VatIdCheck\ValidLevelTwo
 *   name => (string) 'McDonald's Franchise GmbH'
 *   address => (string) 'Campus 21,Liebermannst A01601 AT-2345 Brunn am Gebirge'
 *   valid => (bool) true
 */
```

---

### Databox Download Webservice

```php
// Initialize with previous created session webservice

/** @var SessionWs $sessionWs */
$databoxDownloadWs = new DataboxDownloadWs($sessionWs);

// Get a list of your databox
$databoxList = $databoxDownloadWs->get();
var_dump($databoxList);
/**
 * array
 *   0 =>
 *     CSoellinger\FonWebservices\Result\DataboxDownload\ListItem
 *       stnr => (string) '99 999/9999'
 *       name => (string) 'Mustermann Max'
 *       anbringen => (string) 'STB-ZUSI'
 *       zrvon => (string) ''
 *       zrbis => (string) ''
 *       datbesch => (string) '2019-07-15+02:00'
 *       erltyp => (string) 'B'
 *       fileart => (string) 'PDF'
 *       ts_zust => (string) '2020-11-02T07:04:10.044+01:00'
 *       applkey => (string) '2019-07-15-11.45.07.000000'
 *       filebez => (string) '99_9999999_B_2008-11-20_2008-11-20-100948804630.PDF'
 *       status => (string) ''
 */

// Get one entry by the applkey
$entry = $databoxDownloadWs->getEntry($databoxList[0]->applkey);
var_dump($entry);
/**
 * (string) 'JVBERi0xLjQNJeLjz9MNCjYgMCBvYmoNPDwvTGluZWFyaXplZCAxL0wgNTk1Ny9PIDgvRSAxNzAzL'...
 */
```

---

## Example(s)

The easiest way to start the examples is to start a PHP server:

```bash
php -S localhost:8005 --docroot ./public
```

Now open your browser and go to http://localhost:8005

## Development

### Requirements

- PHP 7.4+ with SOAP extension
- Composer 2.x
- Optional: Podman & Podman Compose for multi-version testing

### Setup

```bash
composer install
vendor/bin/captainhook install -f
```

### Quality Tools

This project uses modern PHP tooling for code quality:

```bash
composer lint              # Check PHP syntax
composer format            # Auto-fix code style with PHP-CS-Fixer
composer format:check      # Check code style (for CI)
composer analyse           # Run PHPStan + Psalm static analysis
composer analyse:phpstan   # Run PHPStan only
composer analyse:psalm     # Run Psalm only
composer test              # Run Pest tests
composer test:coverage     # Run tests with coverage report
composer rector            # Check for refactoring opportunities
composer rector:fix        # Apply automated refactorings
composer check             # Run all quality checks (lint + format + analyse)
```

### Multi-Version Testing with Podman

Test across all supported PHP versions using Podman Compose:

```bash
# Build all containers
podman-compose build

# Test a specific PHP version
podman-compose run --rm php74
podman-compose run --rm php80
podman-compose run --rm php82

# Test all versions at once
bash bin/test-all-versions.sh
```

Supported PHP versions: 7.4, 8.0, 8.1, 8.2, 8.3, 8.4

## Documentation

[Markdown](docs/markdown/README.md)

## Contributing

Contributions are welcome! To contribute, please familiarize yourself with
[CONTRIBUTING.md](.github/CONTRIBUTING.md).

If you found a bug or have an idea feel free to post it here on github.

## Copyright and License

The csoellinger/php-fon-webservices library is free and unencumbered software released into the
public domain. Please see [UNLICENSE](UNLICENSE) for more information.

