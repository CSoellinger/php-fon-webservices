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
use CSoellinger\FonWebservices\Model\VatIdCheckValidLevelOne;
use CSoellinger\FonWebservices\Model\VatIdCheckValidLevelTwo;
use CSoellinger\FonWebservices\Test\Helpers\VatIdRotator;
use CSoellinger\FonWebservices\VatIdCheckWs;

test('check valid vat at level one', function (): void {
    $vatIdCheckWebservice = new VatIdCheckWs($this->sessionWs);
    expect($vatIdCheckWebservice)->toBeInstanceOf(VatIdCheckWs::class);

    $result = null;
    $lastError = null;

    // Try all VAT IDs in the pool until one succeeds
    foreach (VatIdRotator::getAllVatIds() as $uid) {
        usleep(500);
        /** @var VatIdCheckInvalid|VatIdCheckValidLevelOne $result */
        $result = $vatIdCheckWebservice->check($uid);

        // If rate limited (error 1513), try next VAT ID
        if (VatIdRotator::isRateLimited($result)) {
            $lastError = $result;
            continue;
        }

        // Found a working VAT ID, break the loop
        break;
    }

    // If all VAT IDs were rate limited or API has temporary issues, skip the test
    if (VatIdRotator::isRateLimited($result)) {
        $errorCode = $lastError->code ?? $result->code;
        $message = $lastError->msg ?? $result->msg ?? 'Unknown error';

        if ($errorCode === VatIdRotator::ERROR_CODE_TECHNICAL_ERROR) {
            test()->markTestSkipped('FinanzOnline API temporary technical error (code -3): ' . $message);
        } else {
            test()->markTestSkipped('All VAT IDs in pool are rate limited (code 1513) - add more to the pool');
        }
    }

    if ($result instanceof VatIdCheckInvalid) {
        print_r($result);
    }

    expect($result)->toBeInstanceOf(VatIdCheckValidLevelOne::class);
    expect($result->valid)->toBeTrue();
});

test('check valid vat at level two', function (): void {
    $vatIdCheckWebservice = new VatIdCheckWs($this->sessionWs);

    $result = null;
    $lastError = null;

    // Try all VAT IDs in the pool until one succeeds
    foreach (VatIdRotator::getAllVatIds() as $uid) {
        usleep(500);
        /** @var VatIdCheckInvalid|VatIdCheckValidLevelTwo $result */
        $result = $vatIdCheckWebservice->check($uid, VatIdCheckWs::LEVEL_FULL_CHECK);

        // If rate limited (error 1513), try next VAT ID
        if (VatIdRotator::isRateLimited($result)) {
            $lastError = $result;
            continue;
        }

        // Found a working VAT ID, break the loop
        break;
    }

    // If all VAT IDs were rate limited or API has temporary issues, skip the test
    if (VatIdRotator::isRateLimited($result)) {
        $errorCode = $lastError->code ?? $result->code;
        $message = $lastError->msg ?? $result->msg ?? 'Unknown error';

        if ($errorCode === VatIdRotator::ERROR_CODE_TECHNICAL_ERROR) {
            test()->markTestSkipped('FinanzOnline API temporary technical error (code -3): ' . $message);
        } else {
            test()->markTestSkipped('All VAT IDs in pool are rate limited (code 1513) - add more to the pool');
        }
    }

    if ($result instanceof VatIdCheckInvalid) {
        print_r($result);
    }

    expect($result)->toBeInstanceOf(VatIdCheckValidLevelTwo::class);
    expect($result->valid)->toBeTrue();
    expect($result->name)->not->toBeEmpty();
});

test('check invalid vat', function (string $uid): void {
    $vatIdCheckWebservice = new VatIdCheckWs($this->sessionWs);
    expect($vatIdCheckWebservice)->toBeInstanceOf(VatIdCheckWs::class);

    /** @var VatIdCheckInvalid $result */
    $result = $vatIdCheckWebservice->check($uid);
    expect($result)->toBeInstanceOf(VatIdCheckInvalid::class);
    expect($result->valid)->toBeFalse();
})->with([
    ['ATU7231217X'],
]);
