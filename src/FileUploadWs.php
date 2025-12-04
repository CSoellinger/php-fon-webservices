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

use CSoellinger\FonWebservices\Enum\FileUploadType;
use CSoellinger\FonWebservices\Response\ErrorResponse;

use const DIRECTORY_SEPARATOR;

use Exception;

use function file_exists;
use function file_get_contents;
use function implode;
use function in_array;

use InvalidArgumentException;
use SoapClient;

use function strtoupper;

/**
 * FinanzOnline file upload webservice.
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
 * - -4 = Various errors from the XML parser
 * - -5 = You are not allowed to send content like this.
 *
 * @api
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
     * Upload bank data to the FinanzOnline webservice.
     *
     * @param string $xml XML content or path to file
     * @param FileUploadType|string $type File upload type (use FileUploadType enum, string support deprecated)
     * @param bool $isTest True if you want to send the data only for test purposes
     *
     * @throws Exception
     */
    public function upload(string $xml, FileUploadType|string $type, bool $isTest = false): bool
    {
        $typeValue = $this->validateType($type);
        $this->validateXml($xml);

        // If we are not already logged in we can do it here.
        if ($this->sessionWs->isLoggedIn() === false) {
            $this->sessionWs->login();
        }

        $soapParams = [
            'tid' => $this->sessionWs->getCredential()->teId,
            'benid' => $this->sessionWs->getCredential()->benId,
            'id' => $this->sessionWs->getID(),
            'art' => $typeValue,
            'uebermittlung' => $isTest === true ? 'T' : 'P',
            'data' => $xml,
        ];

        /** @var ErrorResponse $response */
        $response = $this->__soapCall('upload', [$soapParams]);
        $returnCode = $response->rc;

        if ($returnCode !== 0) {
            throw new Exception($response->msg, $returnCode);
        }

        return true;
    }

    /**
     * Validate and normalize the file upload type.
     *
     * @param FileUploadType|string $type The file upload type
     *
     * @return string The validated type value
     *
     * @throws InvalidArgumentException If type is not supported
     */
    private function validateType(FileUploadType|string $type): string
    {
        if ($type instanceof FileUploadType) {
            return $type->value;
        }

        $type = strtoupper($type);

        if (in_array($type, self::TYPES) === false) {
            throw new InvalidArgumentException('Wrong type. Choose one of them: ' . implode(', ', self::TYPES));
        }

        return $type;
    }

    /**
     * Validate XML parameter and load from file if path is provided.
     *
     * @param string $xml XML content or path to XML file
     * @param-out string $xml
     */
    private function validateXml(string &$xml): void
    {
        if (file_exists($xml) === true) {
            $content = file_get_contents($xml);
            if ($content !== false) {
                $xml = $content;
            }
        }
    }
}
