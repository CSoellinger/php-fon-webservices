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
    public ?stdClass $l16 = null;

    public ?stdClass $l17 = null;

    public ?stdClass $meldung = null;

    public ?stdClass $mitteilung109a = null;

    /** @var array<stdClass>|null */
    public ?array $sonderausgaben = null;

    /** @var array<stdClass>|null */
    public ?array $leitungsrechte = null;
}
