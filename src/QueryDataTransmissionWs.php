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

use CSoellinger\FonWebservices\Model\QueryDataTransmission;
use CSoellinger\FonWebservices\Response\ErrorResponse;
use CSoellinger\FonWebservices\Response\QueryDataTransmission\QueryResponse;
use Exception;
use InvalidArgumentException;
use SoapClient;
use const DIRECTORY_SEPARATOR;

/**
 * FinanzOnline query data transmission webservice.
 *
 * Requirements for this service:
 * - A registration for FinanzOnline
 * - A user for the web service, which can be created in the user administration of FinanzOnline
 *
 * Error codes
 * -  0 = All fine :)
 * - -1 = The session ID is invalid or expired.
 * - -2 = The web service is currently not available due to maintenance work.
 * - -3 = A technical error has occurred.
 * - -4 = User is not allowed for this function
 * - -5 = The FastNr is not valid
 * - -6 = The period have to be between the (current year - 7) and the current year
 * - -7 = Not allowed to query this FastNr
 *
 * @see https://www.bmf.gv.at/dam/jcr:37d7c48b-d4f3-4c14-ab0c-684c93627089/BMF_Abfrage_Datenuebermittlungen_Webservice.pdf
 */
class QueryDataTransmissionWs extends SoapClient
{
    /**
     * Online WSDL url.
     */
    public const WSDL = 'https://finanzonline.bmf.gv.at/fon/ws/abfrageDatenuebermittlungen.wsdl';

    /**
     * Local WSDL file.
     */
    public const WSDL_LOCAL = __DIR__ . DIRECTORY_SEPARATOR .
                       '..' . DIRECTORY_SEPARATOR .
                       'resources' . DIRECTORY_SEPARATOR .
                       'wsdl' . DIRECTORY_SEPARATOR . 'abfrageDatenuebermittlungen.wsdl';

    public const TYPES = ['LOHNZETTEL', 'SONDERAUSGABEN', 'LEITUNGSRECHTE'];

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
     * tbd.
     *
     * @param string      $fastNr tbd
     * @param string      $period tbd
     * @param null|string $type   tbd
     */
    public function query(string $fastNr, string $period = '', ?string $type = null): QueryDataTransmission
    {
        $this->validateType($type);
        $this->validatePeriod($period);

        // If we are not already logged in we can do it here.
        if ($this->sessionWs->isLoggedIn() === false) {
            $this->sessionWs->login();
        }

        $soapParams = [
            'tid' => $this->sessionWs->getCredential()->teId,
            'benid' => $this->sessionWs->getCredential()->benId,
            'id' => $this->sessionWs->getID(),
            'art' => $type,
            'fastnr' => $fastNr,
            'zeitraum' => $period,
        ];

        /** @var ErrorResponse|QueryResponse $response */
        $response = $this->__soapCall('abfrageDatenuebermittlung', [$soapParams]);
        $returnCode = (int) $response->rc;

        if ($returnCode !== 0) {
            /** @var ErrorResponse $response */
            $response = $response;

            throw new Exception($response->msg, $returnCode);
        }

        /** @var QueryResponse $response */
        $response = $response;

        return QueryDataTransmission::createFromResponse($response);
    }

    /**
     * tbd.
     *
     * @param string $type tbd
     *
     * @throws InvalidArgumentException
     */
    private function validateType(?string &$type): void
    {
        if (is_string($type) === true) {
            $type = strtoupper($type);

            if ($type !== '' && in_array($type, self::TYPES) === false) {
                throw new InvalidArgumentException('Wrong type. Choose one of them: ' . implode(', ', self::TYPES));
            }
        }
    }

    /**
     * tbd.
     *
     * @param string $period tbd
     *
     * @throws InvalidArgumentException
     */
    private function validatePeriod(string &$period): void
    {
        if ($period === '') {
            $period = date('Y');
        }

        if (is_numeric($period) === false || (int) $period < 1000 || (int) $period > 9999) {
            throw new InvalidArgumentException('Period has to be year as a number. Format: YYYY');
        }
    }
}
