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
 * Social status types for employee data.
 */
enum SocialStatus: string
{
    /**
     * No information.
     */
    case NoInformation = 'KEINE ANGABE';

    /**
     * Apprentices.
     */
    case Apprentices = 'LEHRLINGE';

    /**
     * Workers.
     */
    case Workers = 'ARBEITER';

    /**
     * Employees.
     */
    case Employees = 'ANGESTELLTE';

    /**
     * Civil servants.
     */
    case CivilServants = 'BEAMTE';

    /**
     * Contract employees.
     */
    case ContractEmployees = 'VERTRAGSBEDIENSTETE';

    /**
     * ASVG pensioners.
     */
    case AsvgPensioners = 'ASVG-PENSIONISTEN';

    /**
     * Retired civil servants.
     */
    case RetiredCivilServants = 'BEAMTE I.R.';

    /**
     * Other pensioners.
     */
    case OtherPensioners = 'SONSTIGE PENSIONISTEN';
}
