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

use CSoellinger\FonWebservices\Model\VatIdCheckInvalid;
use CSoellinger\FonWebservices\VatIdCheckWs;

test('check invalid vat id format returns error', function (string $uid): void {
    $vatIdCheckWebservice = new VatIdCheckWs($this->sessionWs);

    /** @var VatIdCheckInvalid $result */
    $result = $vatIdCheckWebservice->check($uid);

    // Should return VatIdCheckInvalid for invalid UIDs
    expect($result)->toBeInstanceOf(VatIdCheckInvalid::class);
    expect($result->valid)->toBeFalse();

    // Should have an error code
    expect($result->code)->not->toBe(0);

    // Should have an error message
    expect($result->msg)->not->toBeEmpty();
})->with([
    ['ATU7231217X'], // Invalid checksum
    ['ATU00000000'], // Non-existent UID
    ['ATU9999999999'], // Invalid UID
]);

test('check with invalid vat id returns specific error codes', function (): void {
    $vatIdCheckWebservice = new VatIdCheckWs($this->sessionWs);

    /** @var VatIdCheckInvalid $result */
    $result = $vatIdCheckWebservice->check('ATU7231217X');

    // According to BMF documentation, possible error codes:
    // 1 = UID not valid
    // 4 = UID number is wrong
    // 5 = Applicant's UID is invalid
    // 10 = Member state prohibits this query
    // 101 = UID doesn't start with ATU
    // 103/104 = UID can only be confirmed at level 1 (company group)
    // 105 = UID must be queried individually
    // 1511 = Member state currently not reachable
    expect($result->code)->toBeIn([1, 4, 5, 10, 101, 103, 104, 105, 1511]);
});

test('check vat id with session error', function (): void {
    // Create a session with invalid session ID
    $sessionWs = new CSoellinger\FonWebservices\SessionWs($this->fonCredential);
    $sessionWs->login();

    // Invalidate the session
    $reflector = new ReflectionProperty(CSoellinger\FonWebservices\SessionWs::class, 'id');
    $reflector->setAccessible(true);
    $reflector->setValue($sessionWs, 'invalidSessionId');

    $vatIdCheckWebservice = new VatIdCheckWs($sessionWs);

    /** @var VatIdCheckInvalid $result */
    $result = $vatIdCheckWebservice->check('ATU36975500');

    // Should return invalid result with error code -1 (invalid session)
    expect($result)->toBeInstanceOf(VatIdCheckInvalid::class);
    expect($result->valid)->toBeFalse();
    expect($result->code)->toBe(-1);
    expect($result->msg)->not->toBeEmpty();

    // Manually set empty session ID to prevent destructor from trying to logout again
    $reflector->setValue($sessionWs, '');
});

test('vat id check error messages are descriptive', function (): void {
    $vatIdCheckWebservice = new VatIdCheckWs($this->sessionWs);

    /** @var VatIdCheckInvalid $result */
    $result = $vatIdCheckWebservice->check('ATU00000000');

    // Verify the error message is descriptive (from BMF API)
    expect($result->msg)->not->toBeEmpty();
    expect($result->msg)->not->toBe('Validation error');

    // The message should be in German (BMF API language)
    // or contain useful information
    expect(strlen($result->msg))->toBeGreaterThan(5);
});

test('check uid without ATU prefix returns SOAP validation error', function (): void {
    $vatIdCheckWebservice = new VatIdCheckWs($this->sessionWs);

    /** @var VatIdCheckInvalid $result */
    $result = $vatIdCheckWebservice->check('12345678');

    // When SOAP validates the request before sending to BMF API,
    // it throws a SoapFault which we catch and return as VatIdCheckInvalid
    expect($result)->toBeInstanceOf(VatIdCheckInvalid::class);
    expect($result->valid)->toBeFalse();

    // The error message should be "Validation error" from SOAP
    expect($result->msg)->toBe('Validation error');
});
