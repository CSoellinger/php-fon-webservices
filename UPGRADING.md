# Upgrading from v1.x to v2.0.0

This guide helps you upgrade from version 1.x to version 2.0.0 of php-fon-webservices.

## Requirements

### PHP Version
**BREAKING CHANGE**: PHP 8.1+ is now required.

- ✅ **Supported**: PHP 8.1, 8.2, 8.3, 8.4
- ❌ **No longer supported**: PHP 7.4, 8.0

**Action Required**: Upgrade your PHP version to at least 8.1 before upgrading this library.

```bash
# Check your PHP version
php --version

# Upgrade using composer
composer require csoellinger/php-fon-webservices:^2.0
```

## Backward Compatibility

Most changes in v2.0.0 are **backward compatible**. The library maintains compatibility with existing code through:

- Automatic type conversion (enums accept old string/int values)
- Preserved method signatures with union types
- No changes to public API methods

### What Works Without Changes

The following code continues to work without modifications:

```php
use CSoellinger\FonWebservices\SessionWs;
use CSoellinger\FonWebservices\VatIdCheckWs;
use CSoellinger\FonWebservices\Authentication\FonCredential;

// ✅ This still works - parameters unchanged
$credential = new FonCredential($teId, $teUid, $benId, $benPin);
$session = new SessionWs($credential);

// ✅ This still works - int parameter automatically converted
$vatChecker = new VatIdCheckWs($session);
$result = $vatChecker->check('ATU12345678', 2); // Old int style

// ✅ This still works - string parameter automatically converted
$bankData = new BankDataTransmissionWs($session);
$bankData->upload($xml, 'KTOREG', false); // Old string style
```

## Recommended Migrations

While your existing code will continue to work, we recommend migrating to the new enum-based API for better type safety and IDE support.

### 1. Migrate to Enum Parameters

**Before (v1.x style - still works):**
```php
use CSoellinger\FonWebservices\VatIdCheckWs;

$result = $vatChecker->check('ATU12345678', 1); // Level 1
$result = $vatChecker->check('ATU12345678', 2); // Level 2
```

**After (v2.0 recommended):**
```php
use CSoellinger\FonWebservices\VatIdCheckWs;
use CSoellinger\FonWebservices\Enum\VatIdCheckLevel;

$result = $vatChecker->check('ATU12345678', VatIdCheckLevel::SimpleCheck);
$result = $vatChecker->check('ATU12345678', VatIdCheckLevel::FullCheck);
```

**Benefits:**
- ✅ IDE autocomplete for valid values
- ✅ Type safety - invalid values caught at compile time
- ✅ Self-documenting code
- ✅ No magic numbers

### 2. Replace Deprecated Constants

**Before (deprecated in v2.0):**
```php
use CSoellinger\FonWebservices\VatIdCheckWs;

$result = $vatChecker->check($uid, VatIdCheckWs::LEVEL_SIMPLE_CHECK);
$result = $vatChecker->check($uid, VatIdCheckWs::LEVEL_FULL_CHECK);
```

**After (recommended):**
```php
use CSoellinger\FonWebservices\Enum\VatIdCheckLevel;

$result = $vatChecker->check($uid, VatIdCheckLevel::SimpleCheck);
$result = $vatChecker->check($uid, VatIdCheckLevel::FullCheck);
```

### 3. Migrate Bank Data Type Parameters

**Before (v1.x style - still works):**
```php
$bankData->upload($xml, 'KTOREG'); // Account registration
$bankData->upload($xml, 'KTOZUF'); // Account allocation
```

**After (v2.0 recommended):**
```php
use CSoellinger\FonWebservices\Enum\BankDataType;

$bankData->upload($xml, BankDataType::AccountRegistration);
$bankData->upload($xml, BankDataType::AccountAllocation);
```

### 4. Migrate File Upload Type Parameters

**Before (v1.x style - still works):**
```php
$fileUpload->upload($xml, 'VAT');
$fileUpload->upload($xml, 'BIL');
```

**After (v2.0 recommended):**
```php
use CSoellinger\FonWebservices\Enum\FileUploadType;

$fileUpload->upload($xml, FileUploadType::VAT);
$fileUpload->upload($xml, FileUploadType::BIL);
```

### 5. Migrate Databox Type Parameters

**Before (v1.x style - still works):**
```php
$databox->get(''); // All types
$databox->get('E'); // Electronic submissions
```

**After (v2.0 recommended):**
```php
use CSoellinger\FonWebservices\Enum\DataboxType;

$databox->get(''); // Still valid for all types
$databox->get(DataboxType::E); // Electronic submissions
```

### 6. Migrate Query Data Type Parameters

**Before (v1.x style - still works):**
```php
$query->query($fastNr, $period, 'LOHNZETTEL');
```

**After (v2.0 recommended):**
```php
use CSoellinger\FonWebservices\Enum\QueryDataType;

$query->query($fastNr, $period, QueryDataType::WageSlip);
```

## Available Enums

### VatIdCheckLevel
```php
VatIdCheckLevel::SimpleCheck  // Level 1 - simple validation
VatIdCheckLevel::FullCheck    // Level 2 - with company details
```

### BankDataType
```php
BankDataType::AccountRegistration  // KTOREG
BankDataType::AccountAllocation    // KTOZUF
BankDataType::AccountQuery         // KTOABF
BankDataType::JointMessage         // GMSG
```

### FileUploadType
```php
FileUploadType::BET, FileUploadType::BIL, FileUploadType::DUE,
FileUploadType::EUST, FileUploadType::FPH, FileUploadType::FVAN,
FileUploadType::IVF, FileUploadType::JAHR_ERKL, FileUploadType::JAB,
FileUploadType::KA1, FileUploadType::KOM, FileUploadType::KOMU,
FileUploadType::LFH, FileUploadType::L1, FileUploadType::NOVA,
FileUploadType::RZ, FileUploadType::SB, FileUploadType::SBS,
FileUploadType::SBZ, FileUploadType::STAB, FileUploadType::TVW,
FileUploadType::UEB, FileUploadType::UEB_SA, FileUploadType::U13,
FileUploadType::U30, FileUploadType::VAT, FileUploadType::VATAB,
FileUploadType::VPDGD, FileUploadType::ZEAN, FileUploadType::Type107,
FileUploadType::Type107AB, FileUploadType::Type108, FileUploadType::Type108AB,
FileUploadType::SOER, FileUploadType::DIGI
```

### DataboxType
```php
DataboxType::AE, DataboxType::AF, DataboxType::AK, DataboxType::AZ,
DataboxType::B, DataboxType::DL, DataboxType::E, DataboxType::EU,
DataboxType::FB, DataboxType::GM, DataboxType::I, DataboxType::KG,
DataboxType::M, DataboxType::P, DataboxType::QL, DataboxType::SS
```

### QueryDataType
```php
QueryDataType::WageSlip          // LOHNZETTEL
QueryDataType::SpecialExpenses   // SONDERAUSGABEN
QueryDataType::ManagementRights  // LEITUNGSRECHTE
```

## Testing

After upgrading, run your test suite to ensure everything works:

```bash
# Run tests
composer test

# Run static analysis
composer analyse

# Run all quality checks
composer check
```

## Getting Help

If you encounter issues during the upgrade:

1. Check the [CHANGELOG.md](CHANGELOG.md) for detailed changes
2. Review your PHP version: `php --version`
3. Ensure all dependencies are updated: `composer update`
4. Report issues at: https://github.com/CSoellinger/php-fon-webservices/issues

## Migration Timeline

- **Immediate**: Upgrade PHP to 8.1+
- **Short-term**: Continue using old string/int parameters (still supported)
- **Long-term**: Migrate to enum parameters for better type safety
- **Future**: String/int parameter support may be removed in v3.0.0
