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
     * @var string Fon participator identification
     */
    public string $teId = '';

    /**
     * @var string Fon participator vat id
     */
    public string $teUid = '';

    /**
     * @var string Fon user identification of the web service user
     */
    public string $benId = '';

    /**
     * @var string Fon user pin of the web service user
     */
    public string $benPin = '';

    /**
     * Constructor.
     *
     * @param string $fonTeId Fon participator identification
     * @param string $fonTeUid Fon participator vat id
     * @param string $fonBenId Fon user identification of the web service user
     * @param string $fonBenPin Fon user pin of the web service user
     *
     * @throws InvalidArgumentException If required params are not valid
     */
    public function __construct(string $fonTeId, string $fonTeUid, string $fonBenId, string $fonBenPin)
    {
        if (!$fonTeId || !$fonBenId || !$fonBenPin) {
            throw new InvalidArgumentException(
                'You have to set all necessary informations: fonTeId, fonBenId, fonBenPin',
            );
        }

        if (!preg_match('/[0-9A-Za-z]{8,12}/', $fonTeId)) {
            throw new InvalidArgumentException('Invalid param: fonTeId');
        }

        if ($fonTeUid && !preg_match('/[0-9A-Za-z]{10,24}/', $fonTeUid)) {
            throw new InvalidArgumentException('Invalid param: fonTeUid');
        }

        if (!preg_match('/.{5,12}/', $fonBenId)) {
            throw new InvalidArgumentException('Invalid param: fonBenId');
        }

        if (!preg_match('/.{5,128}/', $fonBenPin)) {
            throw new InvalidArgumentException('Invalid param: fonBenPin');
        }

        $this->teId = $fonTeId;
        $this->teUid = $fonTeUid;
        $this->benId = $fonBenId;
        $this->benPin = $fonBenPin;
    }
}
