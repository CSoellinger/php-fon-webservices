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

class QueryDataTransmissionMsg109aLzData
{
    use StdToClass;

    public ?string $artleiswert = null;
    // ENUM: + Leistungen als Mitglied des Aufsichtsrates, Verwaltungsrates und andere Leistungen
    // von mit der Überwachung der Geschäftsführung beauftragten Personen (im Sinne des § 6
    // Abs. 1 Z 9 lit. b UStG 1994)
    // + Leistungen als Bausparkassenvertreter und Versicherungsvertreter (im Sinne des § 6
    //   Abs. 1 Z 13 UStG 1994)
    // + Leistungen als Stiftungsvorstand (§ 15 Privatstiftungsgesetz)
    // + Leistungen als Vortragender, Lehrender und Unterrichtender
    // + Leistungen als Kolporteur und Zeitungszusteller
    // + Leistungen als Privatgeschäftsvermittler
    // + Leistungen als Funktionär von öffentlich-rechtlichen Körperschaften, wenn die
    //   Tätigkeit zu Funktionsgebühren nach § 29 Z 4 EStG 1988 führt
    // + sonstige Leistungen, die im Rahmen eines freien Dienstvertrages erbracht werden
    //   und der Versicherungspflicht gemäß § 4 Abs. 4 ASVG unterliegen

    public ?float $kzEntgelt = null;

    public ?float $kzSvdna = null;

    public ?float $kzBeitrvorsorge = null;

    public ?float $kzUst = null;
}
