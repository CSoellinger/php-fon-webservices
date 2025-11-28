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

use CSoellinger\FonWebservices\Model\QueryDataTransmission;
use CSoellinger\FonWebservices\QueryDataTransmissionWs;
use Exception;

test('query data transmission', function (): void {
    $queryDataTransmissionWs = new QueryDataTransmissionWs($this->sessionWsDbTest);
    expect($queryDataTransmissionWs)->toBeInstanceOf(QueryDataTransmissionWs::class);

    $result = $queryDataTransmissionWs->query('382572311');
    expect($result)->toBeInstanceOf(QueryDataTransmission::class);
});

test('invalid params', function (string $period, ?string $type): void {
    $queryDataTransmissionWs = new QueryDataTransmissionWs($this->sessionWsDbTest);
    expect($queryDataTransmissionWs)->toBeInstanceOf(QueryDataTransmissionWs::class);

    $queryDataTransmissionWs->query('382572311', $period, $type);
})->with([
    ['', 'ABC'],
    ['100', null],
])->throws(InvalidArgumentException::class);

test('error response', function (): void {
    $queryDataTransmissionWs = new QueryDataTransmissionWs($this->sessionWsDbTest);
    expect($queryDataTransmissionWs)->toBeInstanceOf(QueryDataTransmissionWs::class);

    $queryDataTransmissionWs->query('123456789');
})->throws(Exception::class);
