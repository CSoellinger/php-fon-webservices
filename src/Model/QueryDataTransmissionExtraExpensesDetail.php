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

class QueryDataTransmissionExtraExpensesDetail
{
    use StdToClass;

    /** @var string ... */
    public $organisation = '';

    /** @var float ... */
    public $betrag = 0.0;

    /** @var string|null ... */
    public $status; // ENUM: EFFA, DM
}
