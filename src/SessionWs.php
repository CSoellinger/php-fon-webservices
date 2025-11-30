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

use CSoellinger\FonWebservices\Authentication\FonCredential;
use CSoellinger\FonWebservices\Response\ErrorResponse;
use CSoellinger\FonWebservices\Response\Session\LoginSuccessResponse;
use CSoellinger\FonWebservices\Response\Session\LogoutSuccessResponse;

use const DIRECTORY_SEPARATOR;

use Exception;

use function file_exists;

use SoapClient;

/**
 * FinanzOnline session webservice.
 *
 * This service is used to login and get a session id which is needed for other webservices.
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
 * - -4 = The access codes are not valid.
 * - -5 = The user is locked because of several incorrect login attempts.
 * - -6 = The user is locked.
 * - -7 = The user is not a web service user.
 * - -8 = The participant is locked for FinanzOnline or not authorized to use the web service
 *
 * @see https://www.bmf.gv.at/dam/jcr:570753b2-d511-4194-a03e-33f0ac7371ec/BMF_Session_Webservice_2.pdf
 * @see https://www.bmf.gv.at/dam/jcr:95d0e370-4efb-4ac9-9132-165189ac30ba/BMF_Session_Webservice_Englisch.pdf
 *
 * @example ./examples/session-ws.php Session webservice example
 */
class SessionWs extends SoapClient
{
    /**
     * Online WSDL url
     */
    public const WSDL = 'https://finanzonline.bmf.gv.at/fonws/ws/sessionService.wsdl';

    /**
     * Local WSDL file
     */
    public const WSDL_LOCAL = __DIR__ . DIRECTORY_SEPARATOR .
        '..' . DIRECTORY_SEPARATOR .
        'resources' . DIRECTORY_SEPARATOR .
        'wsdl' . DIRECTORY_SEPARATOR . 'sessionService.wsdl';

    /**
     * @var FonCredential Web service credential class
     */
    private FonCredential $credential;

    /**
     * @var string Web service session id
     */
    private string $id = '';

    /**
     * Constructor.
     *
     * Initialize soap client with local wsdl if available.
     * Otherwise we will take the online wsdl.
     *
     * @param FonCredential $credential credential class used to set some soap params for login/logout
     * @param array<string,mixed> $soapOptions {@link https://www.php.net/manual/en/soapclient.soapclient.php} options
     *
     * @return void
     *              array
     */
    public function __construct(FonCredential $credential, array $soapOptions = [])
    {
        $this->credential = $credential;

        /** @var string $wsdl */
        $wsdl = file_exists(self::WSDL_LOCAL) ? self::WSDL_LOCAL : self::WSDL;

        parent::__construct($wsdl, $soapOptions);
    }

    /**
     * Just be sure to get logged of on class destruction.
     */
    public function __destruct()
    {
        if ($this->isLoggedIn() === true) {
            $this->logout();
        }
    }

    /**
     * Login at session web service.
     *
     * @throws Exception if soap call fails
     * @throws Exception if return code from soap call is greater or less than zero
     */
    public function login(): self
    {
        /** @var ErrorResponse|LoginSuccessResponse $response */
        $response = $this->__soapCall('login', [[
            'tid' => $this->credential->teId,
            'benid' => $this->credential->benId,
            'pin' => $this->credential->benPin,
            'herstellerid' => $this->credential->teUid,
        ],
        ]);

        if ($response->rc !== 0) {
            /** @var ErrorResponse $response */
            $response = $response;

            throw new Exception($response->msg, $response->rc);
        }

        /** @var LoginSuccessResponse $response */
        $response = $response;
        $this->id = $response->id;

        return $this;
    }

    /**
     * Logout from the session web service.
     *
     * @throws Exception if soap call fails
     * @throws Exception if return code from soap call is greater or less than zero
     */
    public function logout(): self
    {
        /** @var ErrorResponse|LogoutSuccessResponse $response */
        $response = $this->__soapCall('logout', [[
            'tid' => $this->credential->teId,
            'benid' => $this->credential->benId,
            'id' => $this->getID(),
        ],
        ]);

        if ($response->rc !== 0) {
            /** @var ErrorResponse $response */
            $response = $response;

            throw new Exception($response->msg, $response->rc);
        }

        $this->id = '';

        return $this;
    }

    /**
     * Check if a session is set, otherwise user can't be logged in.
     */
    public function isLoggedIn(): bool
    {
        return $this->id !== '';
    }

    /**
     * Get the web service session id.
     */
    public function getID(): string
    {
        return $this->id;
    }

    /**
     * Get the credential object used by this session.
     *
     * @return FonCredential The credential object
     */
    public function getCredential(): FonCredential
    {
        return $this->credential;
    }
}
