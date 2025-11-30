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
 * Bank data transmission types.
 *
 * @see https://www.bmf.gv.at/dam/jcr:e9e0dab8-13ac-4073-b6fb-38f3f52b5e56/BMF_Bankendatenuebermittlung_Webservice.pdf
 */
enum BankDataType: string
{
    /**
     * Account registration.
     */
    case AccountRegistration = 'KTOREG';

    /**
     * Account allocation.
     */
    case AccountAllocation = 'KTOZUF';

    /**
     * Account query.
     */
    case AccountQuery = 'KTOABF';

    /**
     * Joint message.
     */
    case JointMessage = 'GMSG';
}
