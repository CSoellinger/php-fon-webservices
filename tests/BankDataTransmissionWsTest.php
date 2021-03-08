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

use CSoellinger\FonWebservices\BankDataTransmissionWs;
use Exception;
use InvalidArgumentException;
use Throwable;

use function file_get_contents;
use function file_put_contents;
use function implode;
use function preg_match;
use function random_int;
use function str_pad;
use function str_replace;
use function substr;
use function unlink;

use const DIRECTORY_SEPARATOR;
use const STR_PAD_LEFT;

/**
 * Testing bank data transmission webservice class.
 *
 * @covers \CSoellinger\FonWebservices\BankDataTransmissionWs
 */
class BankDataTransmissionWsTest extends FonWebservicesTestCase
{
    /**
     * Upload simple test xml.
     */
    public function testUpload(): void
    {
        $bankDataTransmissionWs = new BankDataTransmissionWs($this->sessionWs);
        $this->assertInstanceOf(BankDataTransmissionWs::class, $bankDataTransmissionWs);

        $xmlPath = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'resources', 'test-data', 'Kontenregister.xml']);
        $xmlKontoReg = file_get_contents($xmlPath);
        $match = (array) [];

        preg_match('/<MessageRefId>(.*)<\/MessageRefId>/', (string) $xmlKontoReg, $match);
        $messageRefId = $match[1];
        $count = ((int) substr($messageRefId, -3)) + random_int(1, 500);
        $newMessageRefId = substr($messageRefId, 0, -3) . str_pad((string) $count, 3, '0', STR_PAD_LEFT);

        $xmlPath .= '.tmp.xml';

        file_put_contents($xmlPath, str_replace($messageRefId, $newMessageRefId, (string) $xmlKontoReg));

        $this->assertTrue($bankDataTransmissionWs->upload((string) $xmlPath, 'KTOREG', true));

        unlink($xmlPath);
    }

    /**
     * Upload invalid xml.
     *
     * @throws Exception
     */
    public function testUploadInvalidXml(): void
    {
        $this->expectException(Throwable::class);

        $bankDataTransmissionWs = new BankDataTransmissionWs($this->sessionWs);
        $this->assertInstanceOf(BankDataTransmissionWs::class, $bankDataTransmissionWs);

        $xmlPath = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'resources', 'test-data', 'Kontenregister.xml']);
        $xmlKontoReg = file_get_contents($xmlPath);

        $bankDataTransmissionWs->upload((string) $xmlKontoReg, 'KTOREG', true);
        $bankDataTransmissionWs->upload((string) $xmlKontoReg, 'KTOREG', true);
    }

    /**
     * Upload invalid type.
     *
     * @throws InvalidArgumentException
     */
    public function testInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $bankDataTransmissionWs = new BankDataTransmissionWs($this->sessionWs);
        $bankDataTransmissionWs->upload('', 'XXXXXX', true);
    }
}
