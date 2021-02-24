<?php

/**
 * This file is part of csoellinger/php-fon-webservices
 *
 * csoellinger/php-fon-webservices is free and unencumbered software released
 * into the public domain. For more information, please view the
 * UNLICENSE file that was distributed with this source code.
 *
 * @license https://unlicense.org The Unlicense
 */

declare(strict_types=1);

namespace CSoellinger\FonWebservices\Response\DataboxDownload;

use CSoellinger\FonWebservices\Response\BasicResponse;
use stdClass;

abstract class ListResponse extends BasicResponse
{
    /**
     * @var array<stdClass> ...
     */
    public $result = [];
}
