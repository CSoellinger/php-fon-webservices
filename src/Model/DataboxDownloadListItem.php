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
use DateTime;

class DataboxDownloadListItem
{
    use StdToClass;

    /**
     * @var string ...
     */
    public string $stnr = '';

    /**
     * @var string ...
     */
    public string $name = '';

    /**
     * @var string ...
     */
    public string $anbringen = '';

    /**
     * @var string ...
     */
    public string $zrvon = '';

    /**
     * @var string ...
     */
    public string $zrbis = '';

    /**
     * @var DateTime ...
     */
    public DateTime $datbesch;

    /**
     * @var string ...
     */
    public string $erltyp = '';

    /**
     * @var string ...
     */
    public string $fileart = '';

    /**
     * @var DateTime ...
     */
    public $ts_zust; //phpcs:ignore

    /**
     * @var string ...
     */
    public string $applkey = '';

    /**
     * @var string ...
     */
    public string $filebez = '';

    /**
     * @var string ...
     */
    public string $status = '';

    public function __construct()
    {
        $this->datbesch = new DateTime();
        $this->ts_zust = new DateTime(); //phpcs:ignore
    }
}
