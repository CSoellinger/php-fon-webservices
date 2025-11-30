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

namespace CSoellinger\FonWebservices\Enum;

/**
 * VAT ID check levels for UID verification.
 *
 * @see https://www.bmf.gv.at/dam/jcr:e6acfe5b-f4a5-44f6-8a57-28256efdb850/BMF_UID_Abfrage_Webservice_2.pdf
 */
enum VatIdCheckLevel: int
{
    /**
     * Simple check - only validates if UID is valid.
     */
    case SimpleCheck = 1;

    /**
     * Full check - validates UID and returns company name and address.
     */
    case FullCheck = 2;
}
