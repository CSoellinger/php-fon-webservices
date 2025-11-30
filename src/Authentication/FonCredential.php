<?php

/**
 * This file is part of csoellinger/php-fon-webservices
 *
 * csoellinger/php-fon-webservices is free and unencumbered software released
 * into the public domain. For more information, please view the
 * UNLICENSE file that was distributed with this source code.
 *
 * @license https://unlicense.org The Unlicense
 */

declare(strict_types=1);

namespace CSoellinger\FonWebservices\Authentication;

use InvalidArgumentException;

use function preg_match;

/**
 * FinanzOnline credential class.
 *
 * ```php
 * $credential = new FonCredential($tId, $tUid, $benId, $benPin);
 * ```
 */
class FonCredential
{
    /**
     * Constructor.
     *
     * @param string $teId Fon participator identification
     * @param string $teUid Fon participator vat id
     * @param string $benId Fon user identification of the web service user
     * @param string $benPin Fon user pin of the web service user
     *
     * @throws InvalidArgumentException If required params are not valid
     */
    public function __construct(
        public string $teId = '',
        public string $teUid = '',
        public string $benId = '',
        public string $benPin = '',
    ) {
        if (!$teId || !$benId || !$benPin) {
            throw new InvalidArgumentException(
                'You have to set all necessary informations: fonTeId, fonBenId, fonBenPin',
            );
        }

        if (!preg_match('/[0-9A-Za-z]{8,12}/', $teId)) {
            throw new InvalidArgumentException('Invalid param: fonTeId');
        }

        if ($teUid && !preg_match('/[0-9A-Za-z]{10,24}/', $teUid)) {
            throw new InvalidArgumentException('Invalid param: fonTeUid');
        }

        if (!preg_match('/.{5,12}/', $benId)) {
            throw new InvalidArgumentException('Invalid param: fonBenId');
        }

        if (!preg_match('/.{5,128}/', $benPin)) {
            throw new InvalidArgumentException('Invalid param: fonBenPin');
        }
    }
}
