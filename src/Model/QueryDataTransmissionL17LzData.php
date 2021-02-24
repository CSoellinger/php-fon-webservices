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

class QueryDataTransmissionL17LzData
{
    use StdToClass;

    /** @var float|null */
    public $kz350;

    /** @var float|null */
    public $kz354;

    /** @var float|null */
    public $kz394;

    /** @var float|null */
    public $kz351;

    /** @var float|null */
    public $kz352;

    /** @var float|null */
    public $kzErfinderverg;

    /** @var float|null */
    public $kz356;

    /** @var float|null */
    public $kz738;

    /** @var float|null */
    public $kz357;

    /** @var float|null */
    public $kz347;

    /** @var float|null */
    public $kz736;

    /** @var float|null */
    public $kz737;

    /** @var float|null */
    public $kz739;

    /** @var float|null */
    public $kzUebrigeBezuege;

    /** @var float|null */
    public $kz358;

    /** @var float|null */
    public $kzSpesen;

    /** @var float|null */
    public $kzPensionAg;

    /** @var float|null */
    public $kzKurzarbeit;

    /** @var float|null */
    public $kzAltersteilzeit;

    /** @var float|null */
    public $kzSozZahlung;

    /** @var float|null */
    public $kzAusBezuege;

    /** @var int|null */
    public $werkverkehrMonate;
}
