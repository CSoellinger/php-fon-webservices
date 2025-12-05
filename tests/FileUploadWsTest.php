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

use CSoellinger\FonWebservices\FileUploadWs;
use CSoellinger\FonWebservices\Enum\FileUploadType;

test('upload simple test xml', function (): void {
    $fileUploadWs = new FileUploadWs($this->sessionWs);
    expect($fileUploadWs)->toBeInstanceOf(FileUploadWs::class);

    $xmlPath = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'resources', 'test-data', 'FileUpload.xml']);

    expect($fileUploadWs->upload((string) $xmlPath, 'RZ', true))->toBeTrue();
});

test('upload invalid xml', function (): void {
    $fileUploadWs = new FileUploadWs($this->sessionWs);
    expect($fileUploadWs)->toBeInstanceOf(FileUploadWs::class);

    $xmlPath = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'resources', 'test-data', 'FileUpload.xml']);

    expect($fileUploadWs->upload((string) $xmlPath, 'NOVA', true))->toBeTrue();
})->throws(\Exception::class);

test('upload invalid type', function (): void {
    $fileUploadWs = new FileUploadWs($this->sessionWs);
    $fileUploadWs->upload('', 'XY', true);
})->throws(InvalidArgumentException::class);

test('upload with enum type', function (): void {
    $fileUploadWs = new FileUploadWs($this->sessionWs);
    expect($fileUploadWs)->toBeInstanceOf(FileUploadWs::class);

    $xmlPath = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'resources', 'test-data', 'FileUpload.xml']);

    expect($fileUploadWs->upload((string) $xmlPath, FileUploadType::RZ, true))->toBeTrue();
});
