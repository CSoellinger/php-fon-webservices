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

/**
 * Query result response containing various transmission data types.
 */
abstract class QueryResultResponse
{
    /**
     * @var stdClass|null L16 wage statement data
     */
    public ?stdClass $l16 = null;

    /**
     * @var stdClass|null L17 pension statement data
     */
    public ?stdClass $l17 = null;

    /**
     * @var stdClass|null Report/message data
     */
    public ?stdClass $meldung = null;

    /**
     * @var stdClass|null Message 109a data (contractor payments)
     */
    public ?stdClass $mitteilung109a = null;

    /**
     * @var array<stdClass>|null Special expenses (Sonderausgaben) data
     */
    public ?array $sonderausgaben = null;

    /**
     * @var array<stdClass>|null Management rights (Leitungsrechte) data
     */
    public ?array $leitungsrechte = null;
}
