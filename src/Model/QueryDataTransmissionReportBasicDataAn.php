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

final class QueryDataTransmissionReportBasicDataAn
{
    /** @var string|null ... */
    public ?string $vnrArbeitnehmer = null;

    /** @var string|null ... */
    public ?string $nameArbeitnehmer = null;

    /** @var string|null ... */
    public ?string $titelArbeitnehmer = null;

    /** @var string|null ... */
    public ?string $plzArbeitnehmer = null;

    /** @var string|null ... */
    public ?string $ortArbeitnehmer = null;

    /** @var string|null ... */
    public ?string $adresseArbeitnehmer = null;

    /** @var string|null ... */
    public ?string $sozialestellungwert = null;
    // ENUM: KEINE ANGABE, LEHRLINGE, ARBEITER, ANGESTELLTE, BEAMTE, VERTRAGSBEDIENSTETE,
    //       ASVG-PENSIONISTEN, BEAMTE I.R., SONSTIGE PENSIONISTEN

    /** @var string|null ... */
    public ?string $vnrEhepartner = null;
}
