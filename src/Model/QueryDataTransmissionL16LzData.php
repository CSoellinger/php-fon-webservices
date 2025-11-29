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

use CSoellinger\FonWebservices\Util\Serializer;

use function property_exists;

use stdClass;

class QueryDataTransmissionL16LzData
{
    public ?float $kz210 = null;

    public ?float $kz215 = null;

    public ?float $kz220 = null;

    public ?float $kz225 = null;

    public ?float $kz226 = null;

    public ?float $kz230 = null;

    public ?float $kz240 = null;

    public ?float $kz243 = null;

    public ?float $kz245 = null;

    public ?float $kz260 = null;

    public ?float $kzEinbehalteneSv = null;

    public ?float $kzBezuegeAusland = null;

    public ?float $kzPendlerPauschale = null;

    public ?float $kzGewerkschaftsBeitraege = null;

    public ?float $kzFestsatz = null;

    public ?float $kzSonstigebezuege = null;

    public ?float $kzEinbehaltenelst = null;

    public ?float $kzLfestSatz = null;

    public ?float $kzSonstigebezuege67 = null;

    public ?float $kzFreibetrag105 = null;

    public ?float $kzFreibetrag63 = null;

    public ?float $kzKircheOegb = null;

    public ?float $kzFreibetrag35 = null;

    // @codingStandardsIgnoreStart
    /** @var float|null */
    public $KzNiErfBezuege25;
    // @codingStandardsIgnoreEnd

    public ?int $kzPflegeVonMonat = null;

    public ?int $kzPflegeBisMonat = null;

    public ?float $kzPflegegeld = null;

    public ?float $kzSteuerfreiBezuege26 = null;

    public ?float $kzEntwicklungsHelferin = null;

    public ?float $kzSteuerfrei31 = null;

    public ?int $kzWerkverkehrMonate = null;

    public ?float $kzBapBeitrag = null;

    public ?float $kzBmvBeitrag = null;

    public ?float $pendlereuro = null;

    public ?float $sonderausgaben = null;

    public ?float $ueberlassung = null;

    public ?string $landAuslArbeitsstaette = null;

    // @codingStandardsIgnoreStart
    /** @var float|null */
    public $wkp_expatriates;
    // @codingStandardsIgnoreEnd

    public ?bool $erhoehterVAB = null;

    public ?bool $faboPlus = null;

    public ?int $faboPlusAnzKinder = null;

    public ?float $faboPlusBetrag = null;

    public ?QueryDataTransmissionL16LzDataChild $faboL16Kind = null;

    public static function createFromResponse(stdClass $response): self
    {
        $new = Serializer::deserialize($response, self::class);

        if (property_exists($response, 'faboL16Kind')) {
            $new->faboL16Kind = Serializer::deserialize((object) $response->faboL16Kind, QueryDataTransmissionL16LzDataChild::class);
        }

        return $new;
    }
}
