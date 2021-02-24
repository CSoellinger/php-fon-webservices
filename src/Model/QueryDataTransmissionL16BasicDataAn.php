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

class QueryDataTransmissionL16BasicDataAn
{
    use StdToClass;

    /** @var string|null ... */
    public $vnrArbeitnehmer;

    /** @var string|null ... */
    public $geschlechtArbeitnehmer; // ENUM: MAENNLICH, WEIBLICH

    /** @var string|null ... */
    public $nameArbeitnehmer;

    /** @var string|null ... */
    public $titelArbeitnehmer;

    /** @var string|null ... */
    public $plzArbeitnehmer;

    /** @var string|null ... */
    public $ortArbeitnehmer;

    /** @var string|null ... */
    public $adresseArbeitnehmer;

    /** @var string|null ... */
    public $sozialestellungwert; // ENUM: KEINE ANGABE, LEHRLINGE, ARBEITER, ANGESTELLTE, BEAMTE, VERTRAGSBEDIENSTETE,
                                 //       ASVG-PENSIONISTEN, BEAMTE I.R., SONSTIGE PENSIONISTEN

    /** @var string|null ... */
    public $beschaeftigung; // ENUM: VOLLBESCHAEFTIGUNG, TEILZEITBESCHAEFTIGUNG

    /** @var bool|null ... */
    public $indAvabberuecksichtigt;

    /** @var string|null ... */
    public $vnrEhepartner;

    /** @var bool|null ... */
    public $indAeabberuecksichtigt;

    /** @var int|null ... */
    public $kzAnzahlKinder;
}
