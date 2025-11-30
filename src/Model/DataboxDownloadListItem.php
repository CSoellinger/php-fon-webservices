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
     * @var string Tax number (Steuernummer)
     */
    public string $stnr = '';

    /**
     * @var string Name/description
     */
    public string $name = '';

    /**
     * @var string Application/submission reference
     */
    public string $anbringen = '';

    /**
     * @var string Period start date
     */
    public string $zrvon = '';

    /**
     * @var string Period end date
     */
    public string $zrbis = '';

    /**
     * @var DateTime Date of decision/approval
     */
    public DateTime $datbesch;

    /**
     * @var string Document type
     */
    public string $erltyp = '';

    /**
     * @var string File format/type
     */
    public string $fileart = '';

    /**
     * @var DateTime Timestamp of assignment/allocation
     */
    public $ts_zust; //phpcs:ignore

    /**
     * @var string Application key for document retrieval
     */
    public string $applkey = '';

    /**
     * @var string File name/designation
     */
    public string $filebez = '';

    /**
     * @var string Status of document
     */
    public string $status = '';

    /**
     * Constructor - initializes DateTime properties.
     */
    public function __construct()
    {
        $this->datbesch = new DateTime();
        $this->ts_zust = new DateTime(); //phpcs:ignore
    }
}
