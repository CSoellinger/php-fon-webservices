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

use CSoellinger\FonWebservices\DataboxDownloadWs;
use CSoellinger\FonWebservices\Model\DataboxDownloadListItem;

test('databox download', function (string $type, ?string $from, ?string $to): void {
    $databoxDownloadWs = new DataboxDownloadWs($this->sessionWsDbTest);
    expect($databoxDownloadWs)->toBeInstanceOf(DataboxDownloadWs::class);

    /** @var \DateTime|null $from */
    $from = $from ? new \DateTime($from) : $from;
    /** @var \DateTime|null $to */
    $to = $to ? new \DateTime($to) : $to;

    /** @var array<DataboxDownloadListItem> $result */
    $result = $databoxDownloadWs->get($type, $from, $to);

    expect($result)->toBeArray();

    if (count($result) > 0) {
        /** @var DataboxDownloadListItem $entry */
        $entry = $result[0];

        expect($entry)->toBeInstanceOf(DataboxDownloadListItem::class);

        $resultEntry = $databoxDownloadWs->getEntry($entry->applkey);

        expect($resultEntry)->toBeString();
        expect($resultEntry)->not->toBeEmpty();
    }
})->with([
    ['', null, null],
    ['', 'NOW-30days', null],
    ['', null, 'NOW'],
]);

test('invalid params', function (string $type, ?string $from, ?string $to): void {
    /** @var \DateTime|null $from */
    $from = $from ? new \DateTime($from) : $from;
    /** @var \DateTime|null $to */
    $to = $to ? new \DateTime($to) : $to;

    $databoxDownloadWs = new DataboxDownloadWs($this->sessionWsDbTest);
    expect($databoxDownloadWs)->toBeInstanceOf(DataboxDownloadWs::class);

    $databoxDownloadWs->get($type, $from, $to);
})->with([
    ['WRONG_TYPE', null, null],
    ['WT', null, null],
])->throws(InvalidArgumentException::class);

test('error response', function (string $type, ?string $from, ?string $to): void {
    /** @var \DateTime|null $from */
    $from = $from ? new \DateTime($from) : $from;
    /** @var \DateTime|null $to */
    $to = $to ? new \DateTime($to) : $to;

    $databoxDownloadWs = new DataboxDownloadWs($this->sessionWsDbTest);
    expect($databoxDownloadWs)->toBeInstanceOf(DataboxDownloadWs::class);

    $databoxDownloadWs->get($type, $from, $to);
})->with([
    ['', 'NOW-40days', null],
    ['', 'NOW-30days', 'NOW-10days'],
])->throws(\Exception::class);
