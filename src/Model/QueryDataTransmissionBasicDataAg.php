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

namespace CSoellinger\FonWebservices\Model;

/**
 * Employer (Arbeitgeber) basic data model.
 */
final class QueryDataTransmissionBasicDataAg
{
    /**
     * @var string|null Employer name
     */
    public ?string $nameArbeitgeber = null;

    /**
     * @var string|null Employer postal code
     */
    public ?string $plzArbeitgeber = null;

    /**
     * @var string|null Employer city
     */
    public ?string $ortArbeitgeber = null;

    /**
     * @var string|null Employer address
     */
    public ?string $adresseArbeitgeber = null;
}
