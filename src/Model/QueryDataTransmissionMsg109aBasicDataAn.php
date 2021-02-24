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

use CSoellinger\FonWebservices\Util\StdToClass;

class QueryDataTransmissionMsg109aBasicDataAn
{
    use StdToClass;

    /** @var string|null ... */
    public $vnrAuftragnehmer;

    /** @var string|null ... */
    public $nameAuftragnehmer;

    /** @var string|null ... */
    public $titelAuftragnehmer;

    /** @var string|null ... */
    public $plzAuftragnehmer;

    /** @var string|null ... */
    public $ortAuftragnehmer;

    /** @var string|null ... */
    public $adresseAuftragnehmer;
}
