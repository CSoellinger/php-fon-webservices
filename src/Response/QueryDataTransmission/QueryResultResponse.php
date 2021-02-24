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

namespace CSoellinger\FonWebservices\Response\QueryDataTransmission;

use stdClass;

abstract class QueryResultResponse
{
    /** @var stdClass|null */
    public $l16;

    /** @var stdClass|null */
    public $l17;

    /** @var stdClass|null */
    public $meldung;

    /** @var stdClass|null */
    public $mitteilung109a;

    /** @var array<stdClass>|null */
    public $sonderausgaben;

    /** @var array<stdClass>|null */
    public $leitungsrechte;
}
