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

use function count;

use CSoellinger\FonWebservices\DataboxDownloadWs;
use CSoellinger\FonWebservices\Model\DataboxDownloadListItem;
use DateTime;
use Exception;
use InvalidArgumentException;
use Throwable;

/**
 * Testing databox download webservice class.
 *
 * @internal
 *
 * @covers \CSoellinger\FonWebservices\DataboxDownloadWs
 * @covers \CSoellinger\FonWebservices\Model\DataboxDownloadListItem
 */
class DataboxDownloadWsTest extends FonWebservicesTestCase
{
    /**
     * Login and check if session id is not empty (indicates user is logged in).
     *
     * @testWith ["", null, null]
     *           ["", "NOW-30days", null]
     *           ["", null, "NOW"]
     */
    public function testDatabox(string $type, ?string $from, ?string $to): void
    {
        $databoxDownloadWs = new DataboxDownloadWs($this->sessionWsDbTest);
        $this->assertInstanceOf(DataboxDownloadWs::class, $databoxDownloadWs);

        /** @var DateTime|null $from */
        $from = $from ? new DateTime($from) : $from;
        /** @var DateTime|null $to */
        $to = $to ? new DateTime($to) : $to;

        /** @var array<DataboxDownloadListItem> $result */
        $result = $databoxDownloadWs->get($type, $from, $to);

        $this->assertIsArray($result);

        if (count($result) > 0) {
            /** @var DataboxDownloadListItem $entry */
            $entry = $result[0];

            $this->assertInstanceOf(DataboxDownloadListItem::class, $entry);

            $resultEntry = $databoxDownloadWs->getEntry($entry->applkey);

            $this->assertIsString($resultEntry);
            $this->assertNotEmpty($resultEntry);
        }
    }

    /**
     * Test invalid params
     *
     * @throws InvalidArgumentException
     *
     * @testWith ["WRONG_TYPE", null, null]
     *           ["WT", null, null]
     */
    public function testInvalidParams(string $type, ?string $from, ?string $to): void
    {
        $this->expectException(InvalidArgumentException::class);

        /** @var DateTime|null $from */
        $from = $from ? new DateTime($from) : $from;
        /** @var DateTime|null $to */
        $to = $to ? new DateTime($to) : $to;

        $databoxDownloadWs = new DataboxDownloadWs($this->sessionWsDbTest);
        $this->assertInstanceOf(DataboxDownloadWs::class, $databoxDownloadWs);

        $databoxDownloadWs->get($type, $from, $to);
    }

    /**
     * Test error response.
     *
     * @throws Exception
     *
     * @testWith ["", "NOW-40days", null]
     *           ["", "NOW-30days", "NOW-10days"]
     */
    public function testErrorResponse(string $type, ?string $from, ?string $to): void
    {
        $this->expectException(Throwable::class);

        /** @var DateTime|null $from */
        $from = $from ? new DateTime($from) : $from;
        /** @var DateTime|null $to */
        $to = $to ? new DateTime($to) : $to;

        $databoxDownloadWs = new DataboxDownloadWs($this->sessionWsDbTest);
        $this->assertInstanceOf(DataboxDownloadWs::class, $databoxDownloadWs);

        $databoxDownloadWs->get($type, $from, $to);
    }
}
