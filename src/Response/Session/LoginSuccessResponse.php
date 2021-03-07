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

namespace CSoellinger\FonWebservices\Response\Session;

use CSoellinger\FonWebservices\Response\BasicResponse;

abstract class LoginSuccessResponse extends BasicResponse
{
    /**
     * @var string Session id
     */
    public string $id = '';
}
