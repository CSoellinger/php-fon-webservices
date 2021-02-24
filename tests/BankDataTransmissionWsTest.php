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
        $count = ((int) substr($messageRefId, -3)) + 1;
        $newMessageRefId = substr($messageRefId, 0, -3) . str_pad((string) $count, 3, '0', STR_PAD_LEFT);

        file_put_contents($xmlPath, str_replace($messageRefId, $newMessageRefId, (string) $xmlKontoReg));

        $this->assertTrue($bankDataTransmissionWs->upload((string) $xmlPath, 'KTOREG', true));
    }

    /**
     * Upload invalid xml.
     *
     * @throws Exception
     */
    public function testUploadInvalidXml(): void
    {
        $this->expectException(\Throwable::class);

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
