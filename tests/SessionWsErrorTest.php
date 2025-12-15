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

test('login with invalid credentials returns error code -4', function (): void {
    // Create mock credential with valid format but wrong values
    /** @var FonCredential&PHPUnit\Framework\MockObject\MockObject $badCredential */
    $badCredential = $this
        ->getMockBuilder(FonCredential::class)
        ->disableOriginalConstructor()
        ->getMock();

    $badCredential->teId = '00000000';
    $badCredential->teUid = 'ATU00000000';
    $badCredential->benId = 'wronguser';
    $badCredential->benPin = 'wrongpassword';

    $sessionWebService = new SessionWs($badCredential);

    try {
        $sessionWebService->login();
        expect(true)->toBeFalse('Expected exception to be thrown');
    } catch (Exception $e) {
        // Verify we get the BMF error code -4 (invalid credentials)
        expect($e->getCode())->toBe(-4);
        // Verify we get a proper error message (should be in German from BMF API)
        expect($e->getMessage())->not->toBeEmpty();
        expect($e->getMessage())->toContain('ungültig'); // German for "invalid"
    }
});

test('logout with invalid session returns error code -1', function (): void {
    $sessionWebService = new SessionWs($this->fonCredential);
    $sessionWebService->login();

    // Set an invalid session ID
    $reflector = new ReflectionProperty(SessionWs::class, 'id');
    $reflector->setAccessible(true);
    $reflector->setValue($sessionWebService, 'invalidSessionId123');

    $exceptionThrown = false;
    $errorCode = null;
    $errorMessage = null;

    try {
        $sessionWebService->logout();
    } catch (Exception $e) {
        $exceptionThrown = true;
        $errorCode = $e->getCode();
        $errorMessage = $e->getMessage();
    }

    // Verify exception was thrown
    expect($exceptionThrown)->toBeTrue();

    // Verify we get the BMF error code -1 (invalid/expired session)
    expect($errorCode)->toBe(-1);

    // Verify we get a proper error message (in German from BMF API)
    expect($errorMessage)->not->toBeEmpty();
    expect($errorMessage)->toContain('Session'); // Should mention session

    // Manually set empty session ID to prevent destructor from trying to logout again
    $reflector->setValue($sessionWebService, '');
});

test('error messages contain BMF API response', function (): void {
    /** @var FonCredential&PHPUnit\Framework\MockObject\MockObject $badCredential */
    $badCredential = $this
        ->getMockBuilder(FonCredential::class)
        ->disableOriginalConstructor()
        ->getMock();

    $badCredential->teId = '12345678';
    $badCredential->teUid = 'ATU12345678';
    $badCredential->benId = 'testuser';
    $badCredential->benPin = 'testpassword';

    $sessionWebService = new SessionWs($badCredential);

    $exceptionThrown = false;
    $errorCode = null;
    $errorMessage = null;

    try {
        $sessionWebService->login();
    } catch (Exception $e) {
        $exceptionThrown = true;
        $errorCode = $e->getCode();
        $errorMessage = $e->getMessage();
    }

    // Verify exception was thrown
    expect($exceptionThrown)->toBeTrue();

    // Verify error code is one of the documented BMF error codes
    expect($errorCode)->toBeIn([
        -1, // Invalid/expired session
        -2, // Maintenance
        -3, // Technical error
        -4, // Invalid credentials
        -5, // User locked (multiple failed attempts)
        -6, // User locked
        -7, // Not a webservice user
        -8, // Participant locked/not authorized
    ]);

    // Verify error message is not empty
    expect($errorMessage)->not->toBeEmpty();
});
