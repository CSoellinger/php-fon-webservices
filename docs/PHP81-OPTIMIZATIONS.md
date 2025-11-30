# PHP 8.1+ Optimization Analysis

This document summarizes potential PHP 8.1+ optimizations for the php-fon-webservices library.

**Generated:** 2025-11-30
**PHP Version Support:** 8.1, 8.2, 8.3, 8.4

---

## ✅ Implemented Optimizations

### 1. Array Spread Operator with String Keys (PHP 8.1)
**Status:** ✅ IMPLEMENTED in v2.0.0

Replaced `array_merge()` with the more performant array spread operator.

**Before:**
```php
$soapParams = array_merge([
    'erltyp' => $typeValue,
], $this->getCredentialSoapParams());
```

**After:**
```php
$soapParams = [
    'erltyp' => $typeValue,
    ...$this->getCredentialSoapParams(),
];
```

**Benefits:**
- ✅ Cleaner, more modern syntax
- ✅ Better performance (no function call overhead)
- ✅ More idiomatic PHP 8.1+ code

**Files Changed:**
- `src/DataboxDownloadWs.php` (2 instances)

---

## 🔄 Future Optimization Opportunities

### 2. Readonly Classes (PHP 8.2)
**Status:** ⏳ FUTURE CONSIDERATION

**Challenge:** Many DTO/Model classes are populated AFTER construction via property assignment, which is incompatible with readonly classes.

**Current Pattern:**
```php
class VatIdCheckValidLevelTwo extends VatIdCheckValidLevelOne
{
    public string $name = '';
    public string $address = '';
}

// Usage - properties set after construction
$result = new VatIdCheckValidLevelTwo();
$result->name = $response->name;  // ❌ Would fail with readonly class
$result->address = $response->address;
```

**Required Refactoring:**
Would need to change DTOs to use constructor initialization:

```php
readonly class VatIdCheckValidLevelTwo extends VatIdCheckValidLevelOne
{
    public function __construct(
        public string $name = '',
        public string $address = '',
    ) {
        parent::__construct();
    }
}

// Usage would need to change
$result = new VatIdCheckValidLevelTwo(
    name: $response->name,
    address: $response->address,
);
```

**Candidates for Readonly Classes (if refactored):**

#### Response Classes
- `src/Response/BasicResponse.php` - Abstract base (1 property)
- `src/Response/ErrorResponse.php` - Extends BasicResponse (1 property)
- `src/Response/Session/LoginSuccessResponse.php` - Session ID
- `src/Response/Session/LogoutSuccessResponse.php` - Empty extension
- `src/Response/VatIdCheck/LevelOneSuccessResponse.php` - Empty extension
- `src/Response/VatIdCheck/LevelTwoSuccessResponse.php` - Company details
- `src/Response/DataboxDownload/EntryResponse.php`
- `src/Response/DataboxDownload/ListResponse.php`
- `src/Response/QueryDataTransmission/QueryResponse.php`

#### Model Classes
- `src/Model/VatIdCheckValidLevelOne.php` - Single boolean property
- `src/Model/VatIdCheckInvalid.php` - Error details (3 properties)
- `src/Model/VatIdCheckValidLevelTwo.php` - Company info (2 properties)
- `src/Model/QueryDataTransmissionManagementRight.php` - Simple DTO
- `src/Model/QueryDataTransmissionBasicDataAg.php` - Employer data
- `src/Model/QueryDataTransmissionExtraExpensesDetail.php`
- Various other `QueryDataTransmission*` models (~15 classes)

**Impact:**
- 🔒 Prevents accidental property mutation after initialization
- 🚀 Potential compiler optimizations
- ✅ Better data integrity guarantees
- ❌ Requires significant refactoring of object creation patterns
- ❌ Breaking change - would need v3.0.0

**Recommendation:** Consider for v3.0.0 with DTO refactoring.

---

### 3. Final Class Constants (PHP 8.3+)
**Status:** ⏳ REQUIRES PHP 8.3 MINIMUM

**Challenge:** Current minimum PHP version is 8.1. Using final constants would require bumping to 8.3 minimum.

**Candidates:**
All public WSDL and configuration constants in webservice classes:

```php
// Could be final to prevent override
public const WSDL = 'https://finanzonline.bmf.gv.at/...';
public const WSDL_LOCAL = __DIR__ . DIRECTORY_SEPARATOR . '...';
public const TYPES = ['KTOREG', 'KTOZUF', 'KTOABF', 'GMSG'];
```

**Files with Candidates:**
- `src/SessionWs.php` - WSDL constants (2)
- `src/VatIdCheckWs.php` - WSDL + return code constants (6)
- `src/BankDataTransmissionWs.php` - WSDL constants (2)
- `src/FileUploadWs.php` - WSDL + type constants (3)
- `src/DataboxDownloadWs.php` - WSDL + type constants (3)
- `src/QueryDataTransmissionWs.php` - WSDL + type constants (3)

**Example:**
```php
final public const WSDL = 'https://finanzonline.bmf.gv.at/...';
final public const TYPES = ['KTOREG', 'KTOZUF', 'KTOABF', 'GMSG'];
```

**Benefits:**
- ✅ Prevents subclasses from overriding API configuration
- ✅ Semantic clarity about immutability
- ✅ Compiler can optimize better

**Recommendation:** Consider when minimum PHP version increases to 8.3.

---

## ❌ Not Applicable Optimizations

### 4. Never Return Type (PHP 8.1)
**Status:** ❌ NOT APPLICABLE

**Reason:** The `never` return type is for functions that ALWAYS throw exceptions or run infinitely. In this codebase, exception throwing is conditional.

**Example Analysis:**
```php
public function login(): static  // Returns $this on success
{
    // ...
    if ($response->rc !== 0) {
        throw new Exception($response->msg, $response->rc);  // Conditional throw
    }
    return $this;  // Normal return path exists
}
```

All methods that throw exceptions also have normal return paths, so `never` doesn't apply.

---

### 5. Intersection Types (PHP 8.1)
**Status:** ❌ NOT APPLICABLE

**Reason:** Intersection types (Type1&Type2) require objects implementing multiple interfaces or having strict type constraints. This codebase has:

- Linear single-parent inheritance hierarchies
- Union types already in use (`VatIdCheckLevel|int`)
- SOAP responses with single type per method

No clear candidates for intersection types found.

---

### 6. First-Class Callable Syntax (PHP 8.1)
**Status:** ✅ ALREADY OPTIMIZED

**Finding:** The codebase already uses arrow functions (PHP 7.4+) effectively for callbacks.

**Examples:**
```php
// Already using modern arrow functions
array_map(fn ($val) => Serializer::deserialize($val, QueryDataTransmissionManagementRight::class), $data);

array_map(fn (stdClass $entry) => Serializer::deserialize($entry, DataboxDownloadListItem::class), $result);
```

True first-class callable syntax would look like:
```php
array_map(Serializer::deserialize(...), $data);  // Not applicable here
```

But since we need to pass additional parameters (`$className`), arrow functions remain the best solution.

**Conclusion:** No changes needed - current implementation is optimal.

---

## Performance Impact Summary

| Optimization | Performance Gain | Implementation Cost | Status |
|--------------|-----------------|---------------------|--------|
| Array Spread Operator | Low-Medium | Very Low | ✅ Implemented |
| Readonly Classes | Medium | High (breaking) | ⏳ Future v3.0 |
| Final Constants | Negligible | Low | ⏳ Requires PHP 8.3 |
| Never Return Type | None | N/A | ❌ Not Applicable |
| Intersection Types | None | N/A | ❌ Not Applicable |
| First-Class Callables | None | Already Optimal | ✅ Already Done |

---

## Recommendations

### Immediate (v2.x)
- ✅ **DONE:** Array spread operator for associative arrays
- ✅ **DONE:** Arrow functions for callbacks (already implemented)

### Short-term (v2.x minor releases)
- Monitor PHP 8.3 adoption in user base
- Gather feedback on current v2.0.0 changes

### Long-term (v3.0.0)
Consider for next major version:

1. **Readonly Classes** - Requires DTO refactoring
   - Would be a breaking change
   - Significant architectural improvement
   - Better data integrity guarantees

2. **Final Constants** - When PHP 8.3+ is minimum
   - Non-breaking if done carefully
   - Prevents configuration override

3. **Disjunctive Normal Form (DNF) Types** (PHP 8.2) - If complex type scenarios arise
   - Example: `(Countable&Traversable)|array`
   - Currently no use cases identified

---

## Testing Coverage

All implemented optimizations have been validated:
- ✅ Static analysis (PHPStan, Psalm) - No errors
- ✅ Code style checks (PHP-CS-Fixer) - Compliant
- ✅ Unit tests (Pest) - 30/31 passing (1 external rate limit)
- ✅ Multi-version testing - PHP 8.1, 8.2, 8.3, 8.4

---

## References

- [PHP 8.1 Release Notes](https://www.php.net/releases/8.1/)
- [PHP 8.2 Release Notes](https://www.php.net/releases/8.2/)
- [PHP 8.3 Release Notes](https://www.php.net/releases/8.3/)
- [RFC: Readonly Classes](https://wiki.php.net/rfc/readonly_classes)
- [RFC: First-class callable syntax](https://wiki.php.net/rfc/first_class_callable_syntax)
- [RFC: Array spreading with string keys](https://wiki.php.net/rfc/spread_operator_for_array)

---

*This document should be reviewed when considering v3.0.0 or when minimum PHP version requirements change.*
