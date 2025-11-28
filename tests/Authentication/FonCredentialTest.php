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
