<?php

/**
 * This file is part of csoellinger/php-fon-webservices
 *
 * csoellinger/php-fon-webservices is free and unencumbered software released
 * into the public domain. For more information, please view the
 * UNLICENSE file that was distributed with this source code.
 *
 * @license https://unlicense.org The Unlicense
 */

declare(strict_types=1);

use CSoellinger\FonWebservices\Authentication\FonCredential;

test('initialize with valid credentials', function (string $tId, string $tUid, string $benId, string $benPin): void {
    $fonCredential = new FonCredential($tId, $tUid, $benId, $benPin);

    expect($fonCredential->teId)->not->toBeEmpty();
    expect($fonCredential->teUid)->not->toBeEmpty();
    expect($fonCredential->benId)->not->toBeEmpty();
    expect($fonCredential->benPin)->not->toBeEmpty();
})->with([
    ['11111111k111', 'ATU11111111', 'testuserid1', 'aaAAaaaAAAaaaaAaA7Aaa6aAaa6AaaaE'],
]);

test('invalid arguments throw exception', function (string $tId, string $tUid, string $benId, string $benPin): void {
    new FonCredential($tId, $tUid, $benId, $benPin);
})->with([
    ['', 'ATU11111111', 'testuserid1', 'aaAAaaaAAAaaaaAaA7Aaa6aAaa6AaaaE'],
    ['X', 'ATU11111111', 'testuserid1', 'aaAAaaaAAAaaaaAaA7Aaa6aAaa6AaaaE'],
    ['11111111k111', 'X', 'testuserid1', 'aaAAaaaAAAaaaaAaA7Aaa6aAaa6AaaaE'],
    ['11111111k111', 'ATU11111111', 'X', 'aaAAaaaAAAaaaaAaA7Aaa6aAaa6AaaaE'],
    ['11111111k111', 'ATU11111111', 'testuserid1', 'X'],
])->throws(InvalidArgumentException::class);

test('special characters in teId and teUid are rejected', function (string $tId, string $tUid, string $benId, string $benPin, string $reason): void {
    new FonCredential($tId, $tUid, $benId, $benPin);
})->with([
    ['12345678!!!!', 'ATU11111111', 'testuserid1', 'testpassword', 'teId with special chars'],
    ['!!!12345678', 'ATU11111111', 'testuserid1', 'testpassword', 'teId with prefix special chars'],
    ['1234 5678', 'ATU11111111', 'testuserid1', 'testpassword', 'teId with space'],
    ['11111111k111', 'ATU11111111!', 'testuserid1', 'testpassword', 'teUid with special chars'],
    ['11111111k111', 'ATU 11111111', 'testuserid1', 'testpassword', 'teUid with space'],
])->throws(InvalidArgumentException::class);

test('credentials with exact length requirements work', function (): void {
    // Minimum lengths
    $cred1 = new FonCredential('12345678', 'ATU1234567', 'user1', 'pass1');
    expect($cred1->teId)->toBe('12345678');
    expect($cred1->benId)->toBe('user1');
    expect($cred1->benPin)->toBe('pass1');

    // Maximum lengths
    $cred2 = new FonCredential('123456789012', 'ATU123456789012345678901', 'testuserid12', str_repeat('a', 128));
    expect($cred2->teId)->toBe('123456789012');
    expect($cred2->teUid)->toBe('ATU123456789012345678901');
    expect($cred2->benId)->toBe('testuserid12');
    expect(strlen($cred2->benPin))->toBe(128);
});
