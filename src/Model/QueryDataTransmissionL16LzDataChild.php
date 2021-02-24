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

class QueryDataTransmissionL16LzDataChild
{
    use StdToClass;

    /** @var string|null */
    public $nachnameKind;

    /** @var string|null */
    public $vornameKind;

    /** @var string|null */
    public $wohnsitzStaatKind;

    /** @var bool|null */
    public $wohnsitzStaatwechsel;

    /** @var string|null */
    public $svnrKind;

    /** @var DateTime|null */
    public $vdatgebKind;

    /** @var bool|null */
    public $antragstellerFamilienhilfeBezieher;

    /** @var bool|null */
    public $partnerFamilienhilfeBezieher;

    /** @var bool|null */
    public $unterhaltszahlerFamilienhilfeBezieher;

    /** @var string|null */
    public $beginnGanzerFabo;

    /** @var string|null */
    public $endeGanzerFabo;

    /** @var string|null */
    public $beginnHalberFabo;

    /** @var string|null */
    public $endeHalberFabo;
}
