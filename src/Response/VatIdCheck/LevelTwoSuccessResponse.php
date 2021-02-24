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

namespace CSoellinger\FonWebservices\Response\VatIdCheck;

use CSoellinger\FonWebservices\Response\BasicResponse;

abstract class LevelTwoSuccessResponse extends BasicResponse
{
    /**
     * @var string Company name
     */
    public $name = '';

    /**
     * @var string Address line 1
     */
    public $adrz1 = '';

    /**
     * @var string Address line 2
     */
    public $adrz2 = '';

    /**
     * @var string Address line 3
     */
    public $adrz3 = '';

    /**
     * @var string Address line 4
     */
    public $adrz4 = '';

    /**
     * @var string Address line 5
     */
    public $adrz5 = '';

    /**
     * @var string Address line 6
     */
    public $adrz6 = '';
}
