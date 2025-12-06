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

use CSoellinger\FonWebservices\Enum\QueryDataType;
use CSoellinger\FonWebservices\Model\QueryDataTransmission;
use CSoellinger\FonWebservices\Response\ErrorResponse;
use CSoellinger\FonWebservices\Response\QueryDataTransmission\QueryResponse;

use function date;

use const DIRECTORY_SEPARATOR;

use Exception;

use function file_exists;
use function implode;
use function in_array;

use InvalidArgumentException;

use function is_numeric;
use function is_string;

use SoapClient;

use function strtoupper;

/**
 * FinanzOnline query data transmission webservice.
 *
 * Requirements for this service:
 * - A registration for FinanzOnline
 * - A user for the web service, which can be created in the user administration of FinanzOnline
 *
 * Error codes
 * - 0 = All fine :)
 * - -1 = The session ID is invalid or expired.
 * - -2 = The web service is currently not available due to maintenance work.
 * - -3 = A technical error has occurred.
 * - -4 = User is not allowed for this function
 * - -5 = The FastNr is not valid
 * - -6 = The period have to be between the (current year - 7) and the current year
 * - -7 = Not allowed to query this FastNr
 *
 * @api
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
     * Constructor.
     *
     * @param SessionWs $sessionWs Session webservice
     * @param array<string,mixed> $soapOptions PHP SOAP client options
     */
    public function __construct(
        private readonly SessionWs $sessionWs,
        array $soapOptions = [],
    ) {

        /** @var string $wsdl */
        $wsdl = file_exists(self::WSDL_LOCAL) ? self::WSDL_LOCAL : self::WSDL;

        parent::__construct($wsdl, $soapOptions);
    }

    /**
     * Query data transmissions from FinanzOnline.
     *
     * @param string $fastNr The FASTNR/tax number to query
     * @param string $period The period (year) to query, format YYYY (defaults to current year)
     * @param QueryDataType|string|null $type The query type (use QueryDataType enum, string support deprecated)
     *
     * @return QueryDataTransmission The query result containing transmission data
     *
     * @throws Exception If the query fails
     */
    public function query(string $fastNr, string $period = '', QueryDataType|string|null $type = null): QueryDataTransmission
    {
        $typeValue = $this->validateType($type);
        $this->validatePeriod($period);

        // If we are not already logged in we can do it here.
        if ($this->sessionWs->isLoggedIn() === false) {
            $this->sessionWs->login();
        }

        $soapParams = [
            'tid' => $this->sessionWs->getCredential()->teId,
            'benid' => $this->sessionWs->getCredential()->benId,
            'id' => $this->sessionWs->getID(),
            'art' => $typeValue,
            'fastnr' => $fastNr,
            'zeitraum' => $period,
        ];

        /** @var ErrorResponse|QueryResponse $response */
        $response = $this->__soapCall('abfrageDatenuebermittlung', [$soapParams]);
        $returnCode = $response->rc;

        if ($returnCode !== 0) {
            /** @var ErrorResponse $response */
            throw new Exception($response->msg, $returnCode);
        }

        /** @var QueryResponse $response */
        return QueryDataTransmission::createFromResponse($response);
    }

    /**
     * Validate and normalize the query type parameter.
     *
     * @param QueryDataType|string|null $type The query type to validate
     *
     * @return string|null The validated type value
     *
     * @throws InvalidArgumentException If type is not supported
     */
    private function validateType(QueryDataType|string|null $type): ?string
    {
        if ($type instanceof QueryDataType) {
            return $type->value;
        }

        if (is_string($type) === true) {
            $type = strtoupper($type);

            if ($type !== '' && in_array($type, self::TYPES) === false) {
                throw new InvalidArgumentException('Wrong type. Choose one of them: ' . implode(', ', self::TYPES));
            }
        }

        return $type;
    }

    /**
     * Validate period parameter and default to current year if empty.
     *
     * @param string $period The period (year) to validate
     *
     * @throws InvalidArgumentException If period format is invalid
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
