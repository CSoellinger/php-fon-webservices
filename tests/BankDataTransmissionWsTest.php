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
use function random_int;
use function str_repeat;
use function str_replace;
use function str_shuffle;
use function strlen;
use function substr;
use function unlink;

use const DIRECTORY_SEPARATOR;

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
