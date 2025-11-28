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

use CSoellinger\FonWebservices\Authentication\FonCredential;
use CSoellinger\FonWebservices\SessionWs;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionProperty;

test('initialize with online wsdl', function (): void {
    rename(SessionWs::WSDL_LOCAL, SessionWs::WSDL_LOCAL . '.bak');

    $sessionWebService = new SessionWs($this->fonCredential);
    expect($sessionWebService)->toBeInstanceOf(SessionWs::class);

    rename(SessionWs::WSDL_LOCAL . '.bak', SessionWs::WSDL_LOCAL);
});

test('login and logout', function (): void {
    $sessionWebService = new SessionWs($this->fonCredential);

    $sessionWebService->login();
    expect($sessionWebService->getID())->not->toBeEmpty();
    expect($sessionWebService->isLoggedIn())->toBeTrue();

    $sessionWebService->logout();
    expect($sessionWebService->getID())->toBeEmpty();
    expect($sessionWebService->isLoggedIn())->toBeFalse();
});

test('login with bad credentials', function (): void {
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
})->throws(Exception::class);

test('logout with invalid session id', function (): void {
    $sessionWebService = new SessionWs($this->fonCredential);
    $sessionWebService->login();

    $reflector = new ReflectionProperty(SessionWs::class, 'id');
    $reflector->setAccessible(true);
    $reflector->setValue($sessionWebService, 'invaLidSessiOnId1');

    $sessionWebService->logout();
})->throws(Exception::class);

test('get credential', function (): void {
    $sessionWebService = new SessionWs($this->fonCredential);

    expect($sessionWebService->getCredential())->toBeInstanceOf(FonCredential::class);
});
