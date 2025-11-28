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

namespace CSoellinger\Test\FonWebservices;

use CSoellinger\FonWebservices\Authentication\FonCredential;
use CSoellinger\FonWebservices\SessionWs;
use Exception;

use function getenv;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use RuntimeException;
use SoapClient;
use stdClass;

/**
 * Test case for tests which need a session id.
 *
 * @internal
 *
 * @coversNothing
 */
class FonWebservicesTestCase extends TestCase
{
    /**
     * @var array<string,string> Authentication array
     */
    protected static array $auth = [];

    /**
     * @var SoapClient Session soap client for testing
     */
    protected static SoapClient $sessionSc;

    /**
     * @var MockObject&SessionWs Session webservice class
     */
    protected $sessionWs;

    /**
     * @var MockObject&SessionWs Session webservice class
     */
    protected $sessionWsDbTest;

    /**
     * @var FonCredential&MockObject Finanzonline credential class
     */
    protected $fonCredential;

    /**
     * @var FonCredential&MockObject Finanzonline credential class
     */
    protected $fonCredentialDbTest;

    /**
     * Function is called before setting up the test class.
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$auth = [
            'tid' => (string) getenv('FON_T_ID'),
            'benid' => (string) getenv('FON_BEN_ID'),
            'pin' => (string) getenv('FON_BEN_PIN'),
            'herstellerid' => (string) getenv('FON_T_UID'),
            'uid_tn' => (string) getenv('FON_T_UID'),
        ];

        self::$sessionSc = new SoapClient(SessionWs::WSDL_LOCAL);
    }

    /**
     * Function is called before every test method.
     *
     * @throws Exception
     * @throws RuntimeException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpSessionWs();
        $this->setUpSessionWsDbTest();
    }

    /**
     * Undocumented function.
     *
     * @return FonCredential&MockObject
     */
    private function setUpCredential()
    {
        $fonCredential = $this
            ->getMockBuilder(FonCredential::class)
            ->disableOriginalConstructor()
            ->getMock();

        $fonCredential->benId = self::$auth['benid'];
        $fonCredential->benPin = self::$auth['pin'];
        $fonCredential->teId = self::$auth['tid'];
        $fonCredential->teUid = self::$auth['uid_tn'];

        return $fonCredential;
    }

    /**
     * Undocumented function.
     *
     * @return FonCredential&MockObject
     */
    private function setUpDbTestCredential()
    {
        $fonCredential = $this
            ->getMockBuilder(FonCredential::class)
            ->disableOriginalConstructor()
            ->getMock();

        $fonCredential->benId = (string) getenv('FON_TEST_DB_BEN_ID');
        $fonCredential->benPin = (string) getenv('FON_TEST_DB_BEN_PIN');
        $fonCredential->teId = (string) getenv('FON_TEST_DB_T_ID');
        $fonCredential->teUid = (string) getenv('FON_T_UID');

        return $fonCredential;
    }

    private function setUpSessionWs(): void
    {
        $reflector = new ReflectionProperty(SessionWs::class, 'id');
        $reflector->setAccessible(true);

        $this->fonCredential = $this->setUpCredential();

        $this->sessionWs = $this->getMockBuilder(SessionWs::class)
            ->setConstructorArgs([$this->fonCredential])
            ->disableOriginalConstructor()
            ->getMock();

        $this->sessionWs->method('getCredential')->willReturn($this->fonCredential);
        $this->sessionWs
            ->method('login')
            ->willReturnCallback(function () use ($reflector): SessionWs {
                /** @var stdClass $response */
                $response = self::$sessionSc->__soapCall('login', [[
                    'tid' => $this->fonCredential->teId,
                    'benid' => $this->fonCredential->benId,
                    'pin' => $this->fonCredential->benPin,
                    'herstellerid' => $this->fonCredential->teUid,
                ],
                ]);

                $reflector->setValue($this->sessionWs, $response->id);

                return $this->sessionWs;
            });

        $this->sessionWs
            ->method('logout')
            ->willReturnCallback(function () use ($reflector): SessionWs {
                self::$sessionSc->__soapCall('logout', [[
                    'tid' => $this->fonCredential->teId,
                    'benid' => $this->fonCredential->benId,
                    'id' => $this->sessionWs->getID(),
                ],
                ]);

                $reflector->setValue($this->sessionWs, '');

                return $this->sessionWs;
            });

        $this->sessionWs
            ->method('getID')
            ->willReturnCallback(fn () => $reflector->getValue($this->sessionWs));
    }

    private function setUpSessionWsDbTest(): void
    {
        $reflector = new ReflectionProperty(SessionWs::class, 'id');
        $reflector->setAccessible(true);

        $this->fonCredentialDbTest = $this->setUpDbTestCredential();

        $this->sessionWsDbTest = $this->getMockBuilder(SessionWs::class)
            ->setConstructorArgs([$this->fonCredentialDbTest])
            ->disableOriginalConstructor()
            ->getMock();

        $this->sessionWsDbTest->method('getCredential')->willReturn($this->fonCredentialDbTest);

        $this->sessionWsDbTest
            ->method('login')
            ->willReturnCallback(function () use ($reflector): SessionWs {
                /** @var stdClass $response */
                $response = self::$sessionSc->__soapCall('login', [[
                    'tid' => $this->fonCredentialDbTest->teId,
                    'benid' => $this->fonCredentialDbTest->benId,
                    'pin' => $this->fonCredentialDbTest->benPin,
                    'herstellerid' => $this->fonCredentialDbTest->teUid,
                ],
                ]);

                $reflector->setValue($this->sessionWsDbTest, $response->id);

                return $this->sessionWsDbTest;
            });

        $this->sessionWsDbTest
            ->method('logout')
            ->willReturnCallback(function () use ($reflector): SessionWs {
                self::$sessionSc->__soapCall('logout', [[
                    'tid' => $this->fonCredentialDbTest->teId,
                    'benid' => $this->fonCredentialDbTest->benId,
                    'id' => $this->sessionWsDbTest->getID(),
                ],
                ]);

                $reflector->setValue($this->sessionWsDbTest, '');

                return $this->sessionWsDbTest;
            });

        $this->sessionWsDbTest
            ->method('getID')
            ->willReturnCallback(fn () => $reflector->getValue($this->sessionWsDbTest));
    }
}
