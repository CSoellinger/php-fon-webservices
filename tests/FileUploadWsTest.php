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

namespace CSoellinger\Test\FonWebservices;

use CSoellinger\FonWebservices\FileUploadWs;
use Exception;
use InvalidArgumentException;
use Throwable;

use function implode;

use const DIRECTORY_SEPARATOR;

/**
 * Testing file upload webservice class.
 *
 * @internal
 *
 * @covers \CSoellinger\FonWebservices\FileUploadWs
 */
class FileUploadWsTest extends FonWebservicesTestCase
{
    /**
     * Upload simple test xml.
     */
    public function testUpload(): void
    {
        $fileUploadWs = new FileUploadWs($this->sessionWs);
        $this->assertInstanceOf(FileUploadWs::class, $fileUploadWs);

        $xmlPath = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'resources', 'test-data', 'FileUpload.xml']);

        $this->assertTrue($fileUploadWs->upload((string) $xmlPath, 'RZ', true));
    }

    /**
     * Upload invalid xml.
     *
     * @throws Exception
     */
    public function testUploadInvalidXml(): void
    {
        $this->expectException(Throwable::class);

        $fileUploadWs = new FileUploadWs($this->sessionWs);
        $this->assertInstanceOf(FileUploadWs::class, $fileUploadWs);

        $xmlPath = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'resources', 'test-data', 'FileUpload.xml']);

        $this->assertTrue($fileUploadWs->upload((string) $xmlPath, 'NOVA', true));
    }

    /**
     * Upload invalid type.
     *
     * @throws InvalidArgumentException
     */
    public function testInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $fileUploadWs = new FileUploadWs($this->sessionWs);
        $fileUploadWs->upload('', 'XY', true);
    }
}
