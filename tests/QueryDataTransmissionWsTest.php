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

use CSoellinger\FonWebservices\Model\QueryDataTransmission;
use CSoellinger\FonWebservices\QueryDataTransmissionWs;
use Exception;
use InvalidArgumentException;
use Throwable;

/**
 * Testing query data transmission webservice class.
 *
 * @internal
 *
 * @covers \CSoellinger\FonWebservices\QueryDataTransmissionWs
 * @covers \CSoellinger\FonWebservices\Model\QueryDataTransmission
 * @covers \CSoellinger\FonWebservices\Model\QueryDataTransmissionExtraExpenses
 * @covers \CSoellinger\FonWebservices\Model\QueryDataTransmissionL16
 * @covers \CSoellinger\FonWebservices\Model\QueryDataTransmissionL16LzData
 * @covers \CSoellinger\FonWebservices\Model\QueryDataTransmissionL17
 * @covers \CSoellinger\FonWebservices\Model\QueryDataTransmissionMsg109a
 * @covers \CSoellinger\FonWebservices\Model\QueryDataTransmissionReport
 * @covers \CSoellinger\FonWebservices\Util\StdToClass
 */
class QueryDataTransmissionWsTest extends FonWebservicesTestCase
{
    /**
     * Basic test.
     */
    public function testQueryDataTransmission(): void
    {
        $queryDataTransmissionWs = new QueryDataTransmissionWs($this->sessionWsDbTest);
        $this->assertInstanceOf(QueryDataTransmissionWs::class, $queryDataTransmissionWs);

        $result = $queryDataTransmissionWs->query('382572311');
        $this->assertInstanceOf(QueryDataTransmission::class, $result);
    }

    /**
     * Test invalid params
     *
     * @throws InvalidArgumentException
     *
     * @testWith ["", "ABC"]
     *           ["100", null]
     */
    public function testInvalidParams(string $period, ?string $type): void
    {
        $this->expectException(InvalidArgumentException::class);

        $queryDataTransmissionWs = new QueryDataTransmissionWs($this->sessionWsDbTest);
        $this->assertInstanceOf(QueryDataTransmissionWs::class, $queryDataTransmissionWs);

        $queryDataTransmissionWs->query('382572311', $period, $type);
    }

    /**
     * Test error response.
     *
     * @throws Exception
     */
    public function testErrorResponse(): void
    {
        $this->expectException(Throwable::class);

        $queryDataTransmissionWs = new QueryDataTransmissionWs($this->sessionWsDbTest);
        $this->assertInstanceOf(QueryDataTransmissionWs::class, $queryDataTransmissionWs);

        $queryDataTransmissionWs->query('123456789');
    }
}
