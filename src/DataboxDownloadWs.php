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

use function array_map;
use function array_merge;

use CSoellinger\FonWebservices\Model\DataboxDownloadListItem;
use CSoellinger\FonWebservices\Response\DataboxDownload\EntryResponse;
use CSoellinger\FonWebservices\Response\DataboxDownload\ListResponse;
use CSoellinger\FonWebservices\Response\ErrorResponse;
use DateInterval;
use DateTime;

use const DIRECTORY_SEPARATOR;

use Exception;

use function file_exists;
use function implode;
use function in_array;

use InvalidArgumentException;

use function property_exists;

use SoapClient;
use stdClass;

use function strlen;
use function strtoupper;

/**
 * FinanzOnline databox download webservice.
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
 * - -4 = "ts_zust_von" AND "ts_zust_bis" have to be set.
 * - -5 = "ts_zust_von" can not be before 31 days
 * - -6 = "ts_zust_bis" can not be after 7 days
 *
 * @see https://www.bmf.gv.at/dam/jcr:02d9e9ec-a4f6-4c58-aa3d-aadb8946aed2/BMF_DataBox_Download_Webservice_2.pdf
 */
class DataboxDownloadWs extends SoapClient
{
    /**
     * Online WSDL url.
     */
    public const WSDL = 'https://finanzonline.bmf.gv.at/fon/ws/databoxService.wsdl';

    /**
     * Local WSDL file.
     */
    public const WSDL_LOCAL = __DIR__ . DIRECTORY_SEPARATOR .
        '..' . DIRECTORY_SEPARATOR .
        'resources' . DIRECTORY_SEPARATOR .
        'wsdl' . DIRECTORY_SEPARATOR . 'databoxService.wsdl';

    /**
     * All supported databox types.
     */
    public const TYPES = ['AE', 'AF', 'AK', 'AZ', 'B', 'DL', 'E', 'EU', 'FB', 'GM', 'I', 'KG', 'M', 'P', 'QL', 'SS'];

    /**
     * @var SessionWs session web service
     */
    private SessionWs $sessionWs;

    /**
     * Constructor.
     *
     * @param SessionWs $sessionWs Session webservice
     * @param array<string,mixed> $soapOptions PHP SOAP client options
     */
    public function __construct(SessionWs $sessionWs, array $soapOptions = [])
    {
        $this->sessionWs = $sessionWs;

        /** @var string $wsdl */
        $wsdl = file_exists((string) self::WSDL_LOCAL) ? self::WSDL_LOCAL : self::WSDL;

        parent::__construct($wsdl, $soapOptions);
    }

    /**
     * Get a list for your databox.
     *
     * @param string $type Filter for type. If empty all types are returned. Otherwise choose one of this:
     *                     AE, AF, AK, AZ, B, DL, E, EU, FB, GM, I, KG, M, P, QL, SS
     * @param DateTime|null $from filter from
     * @param DateTime|null $to filter to
     *
     * @return array<DataboxDownloadListItem>
     *
     * @throws Exception
     */
    public function get(string $type = '', ?DateTime $from = null, ?DateTime $to = null)
    {
        $this->validateTypeParam($type);
        $this->validateDateParam($from, $to);

        // If we are not already logged in we can do it here.
        if ($this->sessionWs->isLoggedIn() === false) {
            $this->sessionWs->login();
        }

        // Let's make the soap call
        $soapParams = array_merge([
            'erltyp' => $type,
        ], $this->getCredentialSoapParams());

        if ($from && $to) {
            $soapParams['ts_zust_von'] = $from->format(DateTime::ATOM);
            $soapParams['ts_zust_bis'] = $to->format(DateTime::ATOM);
        }

        /** @var ErrorResponse|ListResponse $response */
        $response = $this->__soapCall('getDatabox', [$soapParams]);

        $this->handleResponse($response);

        /** @var ListResponse $response */
        $response = $response;

        if (property_exists($response, 'result') === false) {
            $response->result = [];
        }

        return array_map(fn (stdClass $entry) => DataboxDownloadListItem::stdToClass($entry), $response->result);
    }

    /**
     * Get one entry from your databox.
     *
     * @param string $applkey You get this param if choose a record from the databox list {@see DataboxDownloadWs::get}
     *
     * @return string Base64 encoded string
     *
     * @throws Exception
     */
    public function getEntry(string $applkey): string
    {
        // If we are not already logged in we can do it here.
        if ($this->sessionWs->isLoggedIn() === false) {
            $this->sessionWs->login();
        }

        // Let's make the soap call
        $soapParams = array_merge([
            'applkey' => $applkey,
        ], $this->getCredentialSoapParams());

        /** @var EntryResponse|ErrorResponse $response */
        $response = $this->__soapCall('getDataboxEntry', [$soapParams]);

        $this->handleResponse($response);

        /** @var EntryResponse $response */
        $response = $response;

        return $response->result;
    }

    /**
     * Undocumented function.
     *
     * @return array<string,string>
     */
    private function getCredentialSoapParams()
    {
        return [
            'tid' => $this->sessionWs->getCredential()->teId,
            'benid' => $this->sessionWs->getCredential()->benId,
            'id' => $this->sessionWs->getID(),
        ];
    }

    /**
     * Validate type filter.
     *
     * @param string $type Type
     *
     * @throws InvalidArgumentException
     */
    private function validateTypeParam(string &$type): void
    {
        if (strlen($type) > 2) {
            throw new InvalidArgumentException('Type has to be a two character string.');
        }

        $type = strtoupper($type);

        if ($type !== '' && in_array($type, self::TYPES) === false) {
            throw new InvalidArgumentException('Wrong type. Choose: ' . implode(', ', self::TYPES));
        }
    }

    /**
     * Validate date filter.
     *
     * @param DateTime|null $from Date from
     * @param DateTime|null $to Date to
     */
    private function validateDateParam(?DateTime &$from, ?DateTime &$to): void
    {
        if ($from && !$to) {
            $to = clone $from;
            $to->add(new DateInterval('P7D'));
        }

        if (!$from && $to) {
            $from = clone $to;
            $from->sub(new DateInterval('P7D'));
        }
    }

    /**
     * Undocumented function.
     *
     * @param EntryResponse|ErrorResponse|ListResponse $response
     *
     * @throws Exception
     */
    private function handleResponse($response): void
    {
        $returnCode = $response->rc;

        if ($returnCode !== 0) {
            /** @var ErrorResponse $response */
            $response = $response;

            throw new Exception($response->msg, $returnCode);
        }
    }
}
