<?php

/**
 * This file is part of csoellinger/php-fon-webservices.
 *
 * csoellinger/php-fon-webservices is free and unencumbered software released
 * into the public domain. For more information, please view the
 * UNLICENSE file that was distributed with this source code.
 *
 * @license https://unlicense.org The Unlicense
 */

declare(strict_types=1);

namespace CSoellinger\FonWebservices\Test\Helpers;

use CSoellinger\FonWebservices\Model\VatIdCheckInvalid;

/**
 * Helper class to rotate through VAT IDs to avoid API rate limits.
 *
 * Each VAT ID can only be queried twice per day (error code 1513).
 * This class provides a pool of VAT IDs to rotate through when one is exhausted.
 */
class VatIdRotator
{
    /**
     * Pool of valid Austrian VAT IDs that can be used for testing.
     * Each UID can be queried twice per day via the API.
     *
     * @var array<string>
     */
    private const VAT_ID_POOL = [
        'ATU43546409',
        'ATU61011906',
        'ATU71704248',
        'ATU69834637',
        'ATU73150467',
        'ATU22737206',
        'ATU76843089',
        'ATU70284159',
        'ATU22135909',
        'ATU71197634',
        'ATU64440322',
        'ATU67624207',
        'ATU65835313',
        'ATU69711706',
        'ATU63568927',
        'ATU43203403',
        'ATU56518600',
        'ATU74138836',
        'ATU48533500',
        'ATU28802105',
        'ATU72579907',
        'ATU68179302',
        'ATU73782828',
        'ATU72696816',
        'ATU69821918',
        'ATU77944101',
        'ATU69742834',
        'ATU68920929',
        'ATU62247713',
        'ATU19434404',
        'ATU64722615',
        'ATU33803701',
        'ATU15255907',
        'ATU72312179',
    ];

    /**
     * Error code indicating the VAT ID has been used too many times today.
     */
    public const ERROR_CODE_RATE_LIMIT = 1513;

    /**
     * Error code indicating a technical error on the API side.
     */
    public const ERROR_CODE_TECHNICAL_ERROR = -3;

    /**
     * Get all available VAT IDs in the pool.
     *
     * @return array<string>
     */
    public static function getAllVatIds(): array
    {
        return self::VAT_ID_POOL;
    }

    /**
     * Check if a result indicates rate limiting or temporary API errors.
     *
     * @param mixed $result The check result
     * @return bool True if the result indicates rate limiting or temporary API error
     */
    public static function isRateLimited($result): bool
    {
        return $result instanceof VatIdCheckInvalid &&
               ($result->code === self::ERROR_CODE_RATE_LIMIT ||
                $result->code === self::ERROR_CODE_TECHNICAL_ERROR);
    }
}
