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
use stdClass;

class QueryDataTransmissionL16LzData
{
    use StdToClass;

    /** @var float|null */
    public $kz210;

    /** @var float|null */
    public $kz215;

    /** @var float|null */
    public $kz220;

    /** @var float|null */
    public $kz225;

    /** @var float|null */
    public $kz226;

    /** @var float|null */
    public $kz230;

    /** @var float|null */
    public $kz240;

    /** @var float|null */
    public $kz243;

    /** @var float|null */
    public $kz245;

    /** @var float|null */
    public $kz260;

    /** @var float|null */
    public $kzEinbehalteneSv;

    /** @var float|null */
    public $kzBezuegeAusland;

    /** @var float|null */
    public $kzPendlerPauschale;

    /** @var float|null */
    public $kzGewerkschaftsBeitraege;

    /** @var float|null */
    public $kzFestsatz;

    /** @var float|null */
    public $kzSonstigebezuege;

    /** @var float|null */
    public $kzEinbehaltenelst;

    /** @var float|null */
    public $kzLfestSatz;

    /** @var float|null */
    public $kzSonstigebezuege67;

    /** @var float|null */
    public $kzFreibetrag105;

    /** @var float|null */
    public $kzFreibetrag63;

    /** @var float|null */
    public $kzKircheOegb;

    /** @var float|null */
    public $kzFreibetrag35;

    // @codingStandardsIgnoreStart
    /** @var float|null */
    public $KzNiErfBezuege25;
    // @codingStandardsIgnoreEnd

    /** @var int|null */
    public $kzPflegeVonMonat;

    /** @var int|null */
    public $kzPflegeBisMonat;

    /** @var float|null */
    public $kzPflegegeld;

    /** @var float|null */
    public $kzSteuerfreiBezuege26;

    /** @var float|null */
    public $kzEntwicklungsHelferin;

    /** @var float|null */
    public $kzSteuerfrei31;

    /** @var int|null */
    public $kzWerkverkehrMonate;

    /** @var float|null */
    public $kzBapBeitrag;

    /** @var float|null */
    public $kzBmvBeitrag;

    /** @var float|null */
    public $pendlereuro;

    /** @var float|null */
    public $sonderausgaben;

    /** @var float|null */
    public $ueberlassung;

    /** @var string|null */
    public $landAuslArbeitsstaette;

    // @codingStandardsIgnoreStart
    /** @var float|null */
    public $wkp_expatriates;
    // @codingStandardsIgnoreEnd

    /** @var bool|null */
    public $erhoehterVAB;

    /** @var bool|null */
    public $faboPlus;

    /** @var int|null */
    public $faboPlusAnzKinder;

    /** @var float|null */
    public $faboPlusBetrag;

    /** @var QueryDataTransmissionL16LzDataChild|null */
    public $faboL16Kind;

    public static function createFromResponse(stdClass $response): self
    {
        $new = self::stdToClass($response);

        if (property_exists($response, 'faboL16Kind')) {
            $new->faboL16Kind = QueryDataTransmissionL16LzDataChild::stdToClass((object) $response->faboL16Kind);
        }

        return $new;
    }
}
