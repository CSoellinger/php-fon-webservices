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

final class QueryDataTransmissionL16LzDataChild
{
    public ?string $nachnameKind = null;

    public ?string $vornameKind = null;

    public ?string $wohnsitzStaatKind = null;

    public ?bool $wohnsitzStaatwechsel = null;

    public ?string $svnrKind = null;

    public ?DateTime $vdatgebKind = null;

    public ?bool $antragstellerFamilienhilfeBezieher = null;

    public ?bool $partnerFamilienhilfeBezieher = null;

    public ?bool $unterhaltszahlerFamilienhilfeBezieher = null;

    public ?string $beginnGanzerFabo = null;

    public ?string $endeGanzerFabo = null;

    public ?string $beginnHalberFabo = null;

    public ?string $endeHalberFabo = null;
}
