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

class QueryDataTransmissionReportLzData
{
    use StdToClass;

    public ?string $leistungsartwert = null; // ENUM: ALTERSTEILZEITG., AMFG-BEIHILFE, AMSG-BEIHILFE, ARBEITSLOSENGELD,
    //                                 AUSBILDUNGSBEIH., BES. ÜBERBRÜCK., BILDUNGSTEILZEITG, ENTFERNUNGSBEIH.,
    //                                 ERW. ÜBERBRÜCK., FACHKRÄFTESTIPEND, GRÜNDUNGSBEIH., KARENZURLAUBSGELD,
    //                                 KARENZURLAUBSH., KINDERBETREU.BEIH, KOMBILOHN, KURSNEBENKOSTEN, NOTSTANDSHILFE,
    //                                 PENS.VORSCHUSS AA, PENS.VORSCHUSS AI, PENS.VORSCHUSS AR, PENS.VORSCHUSS EA,
    //                                 PENS.VORSCHUSS EI, PENS.VORSCHUSS NA, PENS.VORSCHUSS NI, PENS.VORSCHUSS NR,
    //                                 PENS.VORSCHUSS UA, PENS.VORSCHUSS UI, PFLEGEKARENZ, SOLIDARITÄTSPRÄM.,
    //                                 SONDERNOTSTANDSH., SONDERUNTERSTÜTZ, TEILZEITBEIHILFE, ÜBERBRÜCKUNGSH.,
    //                                 ÜBERGANGSGELD, UMSCHULUNGSGELD, VORMERK.KRANKENV., VORMERK.PENS.VER.,
    //                                 VORMERK.SOZ.VERS., VORSCH.KÜND.ENT., VORSCHUSS - REHAB, VORSCHUSS SU,
    //                                 VORSTELLUNGSBEIH., WEITERBILDUNGSG.

    public ?float $kzTagessatz = null;

    public ?int $kzTage = null;

    public ?float $kzLbetrag = null;
}
