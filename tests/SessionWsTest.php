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
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionProperty;
use Throwable;

use function rename;

/**
 * Testing session webservice class.
 *
 * @internal
 *
 * @covers \CSoellinger\FonWebservices\SessionWs
 */
class SessionWsTest extends FonWebservicesTestCase
{
    /**
     * Test initializing webservice with online wsdl url
     */
    public function testInitializeWithOnlineWsdl(): void
    {
        rename(SessionWs::WSDL_LOCAL, SessionWs::WSDL_LOCAL . '.bak');

        $sessionWebService = new SessionWs($this->fonCredential);
        $this->assertInstanceOf(SessionWs::class, $sessionWebService);

        rename(SessionWs::WSDL_LOCAL . '.bak', SessionWs::WSDL_LOCAL);
    }

    /**
     * Login and check if session id is not empty (indicates user is logged in)
     */
    public function testLoginAndLogout(): void
    {
        $sessionWebService = new SessionWs($this->fonCredential);

        $sessionWebService->login();
        $this->assertNotEmpty($sessionWebService->getID());
        $this->assertTrue($sessionWebService->isLoggedIn());

        $sessionWebService->logout();
        $this->assertEmpty($sessionWebService->getID());
        $this->assertFalse($sessionWebService->isLoggedIn());
    }

    /**
     * Login with bad credentials
     *
     * @throws Throwable
     */
    public function testLoginWithBadCredentials(): void
    {
        $this->expectException(Throwable::class);

        /** @var FonCredential&MockObject $badCredential */
        $badCredential = $this
            ->getMockBuilder(FonCredential::class)
            ->disableOriginalConstructor()
            ->getMock();

        $badCredential->teId = '00000000k000';
        $badCredential->teUid = 'ATU00000000';
        $badCredential->benId = 'notexists1';
        $badCredential->benPin = 'aaAAaaAAaaAAaaAAaaAAaaAAaaAAaaAA';

        $sessionWebService = new SessionWs($badCredential);
        $sessionWebService->Login();
    }

    /**
     * Logout with invalid session id.
     *
     * @throws Throwable
     */
    public function testLogoutWithInvalidSessionId(): void
    {
        $this->expectException(Throwable::class);

        $sessionWebService = new SessionWs($this->fonCredential);
        $sessionWebService->login();

        $reflector = new ReflectionProperty(SessionWs::class, 'id');
        $reflector->setAccessible(true);
        $reflector->setValue($sessionWebService, 'invaLidSessiOnId1');

        $sessionWebService->logout();
    }

    public function testGetCredential(): void
    {
        $sessionWebService = new SessionWs($this->fonCredential);

        $this->assertInstanceOf(FonCredential::class, $sessionWebService->getCredential());
    }
}
