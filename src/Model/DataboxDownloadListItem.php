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

use DateTime;

class DataboxDownloadListItem
{
    /**
     * @var string ...
     */
    public $stnr = '';

    /**
     * @var string ...
     */
    public $name = '';

    /**
     * @var string ...
     */
    public $anbringen = '';

    /**
     * @var string ...
     */
    public $zrvon = '';

    /**
     * @var string ...
     */
    public $zrbis = '';

    /**
     * @var DateTime ...
     */
    public $datbesch;

    /**
     * @var string ...
     */
    public $erltyp = '';

    /**
     * @var string ...
     */
    public $fileart = '';

    // @codingStandardsIgnoreStart
    /**
     * @var DateTime ...
     */
    public $ts_zust;
    // @codingStandardsIgnoreEnd

    /**
     * @var string ...
     */
    public $applkey = '';

    /**
     * @var string ...
     */
    public $filebez = '';

    /**
     * @var string ...
     */
    public $status = '';

    public function __construct()
    {
        $this->datbesch = new DateTime();
        $this->ts_zust = new DateTime();
    }
}
