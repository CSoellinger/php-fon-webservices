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
 * Query data transmission types.
 *
 * @see https://www.bmf.gv.at/dam/jcr:37d7c48b-d4f3-4c14-ab0c-684c93627089/BMF_Abfrage_Datenuebermittlungen_Webservice.pdf
 */
enum QueryDataType: string
{
    /**
     * Wage slip.
     */
    case WageSlip = 'LOHNZETTEL';

    /**
     * Special expenses.
     */
    case SpecialExpenses = 'SONDERAUSGABEN';

    /**
     * Management rights.
     */
    case ManagementRights = 'LEITUNGSRECHTE';
}
