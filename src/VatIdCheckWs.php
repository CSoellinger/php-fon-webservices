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

namespace CSoellinger\FonWebservices;

use CSoellinger\FonWebservices\Model\VatIdCheckInvalid;
use CSoellinger\FonWebservices\Model\VatIdCheckValidLevelOne;
use CSoellinger\FonWebservices\Model\VatIdCheckValidLevelTwo;
use CSoellinger\FonWebservices\Response\ErrorResponse;
use CSoellinger\FonWebservices\Response\VatIdCheck\LevelOneSuccessResponse;
use CSoellinger\FonWebservices\Response\VatIdCheck\LevelTwoSuccessResponse;
use SoapClient;

use const DIRECTORY_SEPARATOR;
use const PHP_EOL;

/**
 * Check a vat id by the finanzonline UidAbfrage webservice.
 *
 * Requirements for this service:
 * - A registration for FinanzOnline
 * - A user for the web service, which can be created in the user administration of FinanzOnline
 *
 * Return codes
 * -    0 = Die UID des Erwerbers ist gültig.
 * -   -1 = Die Session ID ist ungültig oder abgelaufen.
 * -   -2 = Der Aufruf des Webservices ist derzeit wegen Wartungsarbeiten nicht möglich.
 * -   -3 = Es ist ein technischer Fehler aufgetreten.
 * -   -4 = Dieser Teilnehmer ist für diese Funktion nicht berechtigt.
 * -    1 = Die UID des Erwerbers ist nicht gültig.
 * -    4 = Die UID-Nummer des Erwerbers ist falsch.
 * -    5 = Die UID-Nummer des Antragstellers ist ungültig.
 * -   10 = Der angegebene Mitgliedstaat verbietet diese Abfrage.
 * -  101 = UID beginnt nicht mit ATU.
 * -  103 = Die angefragte UID-Nummer kann im FinanzOnline nur in Stufe 1 bestätigt werden,
 *          da diese UID-Nummer zu einer Unternehmensgruppe (Umsatzsteuergruppe) gehört.
 * -  104 = Die angefragte UID-Nummer kann im FinanzOnline nur in Stufe 1 bestätigt werden,
 *          da diese UID-Nummer zu einer Unternehmensgruppe (Umsatzsteuergruppe) gehört.
 * -  105 = Die UID-Nummer ist über FinanzOnline einzeln abzufragen.
 * - 1511 = Der angegebene Mitgliedstaat ist derzeit nicht erreichbar.
 *
 * @see https://www.bmf.gv.at/dam/jcr:e6acfe5b-f4a5-44f6-8a57-28256efdb850/BMF_UID_Abfrage_Webservice_2.pdf
 */
class VatIdCheckWs extends SoapClient
{
    /**
     * Online WSDL url.
     */
    public const WSDL = 'https://finanzonline.bmf.gv.at/fonuid/ws/uidAbfrageService.wsdl';

    /**
     * Local WSDL file.
     */
    public const WSDL_LOCAL = __DIR__ . DIRECTORY_SEPARATOR .
                       '..' . DIRECTORY_SEPARATOR .
                       'resources' . DIRECTORY_SEPARATOR .
                       'wsdl' . DIRECTORY_SEPARATOR . 'uidAbfrageService.wsdl';

    /**
     * Check at level one.
     */
    public const LEVEL_SIMPLE_CHECK = 1;

    /**
     * Check at level two.
     */
    public const LEVEL_FULL_CHECK = 2;

    /**
     * All return code numbers for valid vat id's.
     */
    public const VALID_RETURN_CODES = [0];

    /**
     * All return code numbers for invalid vat id's.
     */
    public const NOT_VALID_RETURN_CODES = [1, 4];

    /**
     * @var SessionWs session web service
     */
    private $sessionWs;

    /**
     * Constructor.
     *
     * @param SessionWs           $sessionWs   Session webservice
     * @param array<string,mixed> $soapOptions PHP SOAP client options
     */
    public function __construct(SessionWs $sessionWs, array $soapOptions = [])
    {
        $this->sessionWs = $sessionWs;

        $wsdl = file_exists(self::WSDL_LOCAL) ? self::WSDL_LOCAL : self::WSDL;

        parent::__construct($wsdl, $soapOptions);
    }

    /**
     * Check an uid (vat id).
     *
     * @param string $uid   Vat id to check
     * @param int    $level Choose level 1 or 2. With 2 you will also get some company name and address.
     *
     * @return VatIdCheckInvalid|VatIdCheckValidLevelOne|VatIdCheckValidLevelTwo
     */
    public function check(string $uid, int $level = 1)
    {
        // If level is less than one or more than two we reset it to one.
        $level = $level < 1 || $level > 2 ? 1 : $level;

        // If we are not already logged in we can do it here.
        if ($this->sessionWs->isLoggedIn() === false) {
            $this->sessionWs->login();
        }

        // Let's make the soap call
        /** @var ErrorResponse|LevelOneSuccessResponse|LevelTwoSuccessResponse $response */
        $response = $this->__soapCall('uidAbfrage', [[
            'tid' => $this->sessionWs->getCredential()->teId,
            'benid' => $this->sessionWs->getCredential()->benId,
            'uid_tn' => $this->sessionWs->getCredential()->teUid,
            'id' => $this->sessionWs->getID(),
            'uid' => $uid,
            'stufe' => $level,
        ],
        ]);

        $result = null;
        $returnCode = (int) $response->rc;

        if (in_array($returnCode, self::VALID_RETURN_CODES, true) === true) {
            // Valid
            if ($level === 1) {
                return new VatIdCheckValidLevelOne();
            }

            /** @var LevelTwoSuccessResponse $response */
            $response = $response;

            $result = new VatIdCheckValidLevelTwo();
            $result->name = $response->name;
            $result->address = str_replace('  ', ' ', trim(implode(PHP_EOL, [
                $response->adrz1,
                $response->adrz2,
                $response->adrz3,
                $response->adrz4,
                $response->adrz5,
                $response->adrz6,
            ])));

            return $result;
        }
        if (in_array($returnCode, [103, 104], true) === true) {
            // Special error should happen only at full check level...
            // re-check at level 1 and return the result
            // @codeCoverageIgnoreStart
            $simpleResult = $this->check($uid, self::LEVEL_SIMPLE_CHECK);

            if ($simpleResult->valid === false) {
                return $simpleResult;
            }

            return new VatIdCheckValidLevelTwo();
            // @codeCoverageIgnoreEnd
        }

        // Invalid / Error
        /** @var ErrorResponse $response */
        $response = $response;

        $result = new VatIdCheckInvalid();
        $result->code = $returnCode;
        $result->msg = $response->msg;

        return $result;
    }
}
