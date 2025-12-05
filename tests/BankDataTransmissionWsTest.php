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

use CSoellinger\FonWebservices\BankDataTransmissionWs;
use CSoellinger\FonWebservices\Enum\BankDataType;

test('upload simple test xml', function (): void {
    $bankDataTransmissionWs = new BankDataTransmissionWs($this->sessionWs);
    expect($bankDataTransmissionWs)->toBeInstanceOf(BankDataTransmissionWs::class);

    $xmlPath = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'resources', 'test-data', 'Kontenregister.xml']);
    $xmlContent = (string) file_get_contents($xmlPath);
    $xmlPath .= '.tmp.xml';

    $newFastNr = '';
    while (strlen($newFastNr) < 9) {
        $newFastNr .= random_int(1, 9);
    }

    $newMessageRefId = $newFastNr . substr(str_shuffle(str_repeat('abcdefghijklmnopqrstuvwxyz', 5)), 0, 5);
    while (strlen($newMessageRefId) < 25) {
        $newMessageRefId .= random_int(1, 9);
    }

    $xmlContent = str_replace('987654321abcde000', $newMessageRefId, $xmlContent);
    $xmlContent = str_replace('987654321', $newFastNr, $xmlContent);

    file_put_contents($xmlPath, $xmlContent);

    expect($bankDataTransmissionWs->upload((string) $xmlPath, 'KTOREG', true))->toBeTrue();

    unlink($xmlPath);
});

test('upload invalid xml', function (): void {
    $bankDataTransmissionWs = new BankDataTransmissionWs($this->sessionWs);
    expect($bankDataTransmissionWs)->toBeInstanceOf(BankDataTransmissionWs::class);

    $xmlPath = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'resources', 'test-data', 'Kontenregister.xml']);
    $xmlKontoReg = file_get_contents($xmlPath);

    $bankDataTransmissionWs->upload((string) $xmlKontoReg, 'KTOREG', true);
    $bankDataTransmissionWs->upload((string) $xmlKontoReg, 'KTOREG', true);
})->throws(\Exception::class);

test('upload invalid type', function (): void {
    $bankDataTransmissionWs = new BankDataTransmissionWs($this->sessionWs);
    $bankDataTransmissionWs->upload('', 'XXXXXX', true);
})->throws(InvalidArgumentException::class);

test('upload with enum type', function (): void {
    $bankDataTransmissionWs = new BankDataTransmissionWs($this->sessionWs);
    expect($bankDataTransmissionWs)->toBeInstanceOf(BankDataTransmissionWs::class);

    $xmlPath = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'resources', 'test-data', 'Kontenregister.xml']);
    $xmlContent = (string) file_get_contents($xmlPath);
    $xmlPath .= '.tmp.xml';

    $newFastNr = '';
    while (strlen($newFastNr) < 9) {
        $newFastNr .= random_int(1, 9);
    }

    $newMessageRefId = $newFastNr . substr(str_shuffle(str_repeat('abcdefghijklmnopqrstuvwxyz', 5)), 0, 5);
    while (strlen($newMessageRefId) < 25) {
        $newMessageRefId .= random_int(1, 9);
    }

    $xmlContent = str_replace('987654321abcde000', $newMessageRefId, $xmlContent);
    $xmlContent = str_replace('987654321', $newFastNr, $xmlContent);

    file_put_contents($xmlPath, $xmlContent);

    expect($bankDataTransmissionWs->upload((string) $xmlPath, BankDataType::AccountRegistration, true))->toBeTrue();

    unlink($xmlPath);
});
