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
use CSoellinger\FonWebservices\VatIdCheckWs;

test('check valid vat at level one', function (string $uid): void {
    $vatIdCheckWebservice = new VatIdCheckWs($this->sessionWs);
    expect($vatIdCheckWebservice)->toBeInstanceOf(VatIdCheckWs::class);

    usleep(500);
    /** @var VatIdCheckInvalid|VatIdCheckValidLevelOne $result */
    $result = $vatIdCheckWebservice->check($uid);

    if ($result instanceof VatIdCheckInvalid) {
        print_r($result);
    }

    expect($result)->toBeInstanceOf(VatIdCheckValidLevelOne::class);
    expect($result->valid)->toBeTrue();
})->with([
    ['ATU72312179'],
]);

test('check valid vat at level two', function (string $uid): void {
    $vatIdCheckWebservice = new VatIdCheckWs($this->sessionWs);

    usleep(500);
    /** @var VatIdCheckInvalid|VatIdCheckValidLevelTwo $result */
    $result = $vatIdCheckWebservice->check($uid, VatIdCheckWs::LEVEL_FULL_CHECK);

    if ($result instanceof VatIdCheckInvalid) {
        print_r($result);
    }

    expect($result)->toBeInstanceOf(VatIdCheckValidLevelTwo::class);
    expect($result->valid)->toBeTrue();
    expect($result->name)->not->toBeEmpty();
})->with([
    ['ATU72312179'],
]);

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
