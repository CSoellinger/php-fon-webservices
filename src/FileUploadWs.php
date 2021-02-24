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

use CSoellinger\FonWebservices\Response\ErrorResponse;
use Exception;
use InvalidArgumentException;
use SoapClient;

use const DIRECTORY_SEPARATOR;

/**
 * FinanzOnline file upload webservice.
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
 * - -4 = Various errors from the XML parser
 * - -5 = You are not allowed to send content like this.
 *
 * @see https://www.bmf.gv.at/dam/jcr:7f3258d4-5d58-455d-9bfe-a352a4effd73/BMF_File_Upload_Webservice_2.pdf
 */
class FileUploadWs extends SoapClient
{
    /**
     * Online WSDL url.
     */
    public const WSDL = 'https://finanzonline.bmf.gv.at/fon/ws/fileuploadService.wsdl';

    /**
     * Local WSDL file.
     */
    public const WSDL_LOCAL = __DIR__ . DIRECTORY_SEPARATOR .
                       '..' . DIRECTORY_SEPARATOR .
                       'resources' . DIRECTORY_SEPARATOR .
                       'wsdl' . DIRECTORY_SEPARATOR . 'fileuploadService.wsdl';

    /**
     * All supported fileupload types.
     */
    public const TYPES = [
        'BET', 'BIL', 'DUE', 'EUST', 'FPH', 'FVAN', 'IVF', 'JAHR_ERKL', 'JAB', 'KA1', 'KOM', 'KOMU', 'LFH', 'L1',
        'NOVA', 'RZ', 'SB', 'SBS', 'SBZ', 'STAB', 'TVW', 'UEB', 'UEB_SA', 'U13', 'U30', 'VAT', 'VATAB', 'VPDGD',
        'ZEAN', '107', '107AB', '108', '108AB', 'SOER', 'DIGI',
    ];

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
     * Upload bank data to the FinanzOnline webservice.
     *
     * @param string $xml    XML content or path to file
     * @param string $type   Choose BET, BIL, DUE, EUST, FPH, FVAN, IVF, JAHR_ERKL, JAB, KA1, KOM, KOMU, LFH, L1,
     *                       NOVA, RZ, SB, SBS, SBZ, STAB, TVW, UEB, UEB_SA, U13, U30, VAT, VATAB, VPDGD, ZEAN, 107,
     *                       107AB, 108, 108AB, SOER, DIGI
     * @param bool   $isTest True if you want to send the data only for test purposes
     *
     * @throws Exception
     */
    public function upload(string $xml, string $type, bool $isTest = false): bool
    {
        $this->validateType($type);
        $this->validateXml($xml);

        // If we are not already logged in we can do it here.
        if ($this->sessionWs->isLoggedIn() === false) {
            $this->sessionWs->login();
        }

        $soapParams = [
            'tid' => $this->sessionWs->getCredential()->teId,
            'benid' => $this->sessionWs->getCredential()->benId,
            'id' => $this->sessionWs->getID(),
            'art' => $type,
            'uebermittlung' => $isTest === true ? 'T' : 'P',
            'data' => $xml,
        ];

        /** @var ErrorResponse $response */
        $response = $this->__soapCall('upload', [$soapParams]);
        $returnCode = (int) $response->rc;

        if ($returnCode !== 0) {
            throw new Exception($response->msg, $returnCode);
        }

        return true;
    }

    /**
     * Undocumented function.
     *
     * @param string $type ...
     *
     * @throws InvalidArgumentException
     */
    private function validateType(string &$type): void
    {
        $type = strtoupper($type);

        if (in_array($type, self::TYPES) === false) {
            throw new InvalidArgumentException('Wrong type. Choose one of them: ' . implode(', ', self::TYPES));
        }
    }

    /**
     * Undocumented function.
     *
     * @param string $xml ...
     */
    private function validateXml(string &$xml): void
    {
        if (file_exists($xml) === true) {
            $xml = file_get_contents($xml);
        }
    }
}
