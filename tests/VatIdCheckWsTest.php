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

use CSoellinger\FonWebservices\Model\VatIdCheckInvalid;
use CSoellinger\FonWebservices\Model\VatIdCheckValidLevelOne;
use CSoellinger\FonWebservices\Model\VatIdCheckValidLevelTwo;
use CSoellinger\FonWebservices\VatIdCheckWs;

use function print_r;
use function usleep;

/**
 * Testing session webservice class.
 *
 * @internal
 *
 * @covers \CSoellinger\FonWebservices\VatIdCheckWs
 */
class VatIdCheckWsTest extends FonWebservicesTestCase
{
    /**
     * Check vat id at level one.
     *
     * @param string $uid Vat id
     *
     * @testWith ["ATU72312179"]
     */
    public function testCheckValidVatLevelOne(string $uid): void
    {
        $vatIdCheckWebservice = new VatIdCheckWs($this->sessionWs);
        $this->assertInstanceOf(VatIdCheckWs::class, $vatIdCheckWebservice);

        usleep(500);
        /** @var VatIdCheckInvalid|VatIdCheckValidLevelOne $result */
        $result = $vatIdCheckWebservice->check($uid);

        if ($result instanceof VatIdCheckInvalid) {
            print_r($result);
        }

        $this->assertInstanceOf(VatIdCheckValidLevelOne::class, $result);
        $this->assertTrue($result->valid);
    }

    /**
     * Check vat id at level two.
     *
     * @param string $uid Vat id
     *
     * @testWith ["ATU72312179"]
     */
    public function testCheckValidVatLevelTwo(string $uid): void
    {
        $vatIdCheckWebservice = new VatIdCheckWs($this->sessionWs);

        usleep(500);
        /** @var VatIdCheckInvalid|VatIdCheckValidLevelTwo $result */
        $result = $vatIdCheckWebservice->check($uid, VatIdCheckWs::LEVEL_FULL_CHECK);

        if ($result instanceof VatIdCheckInvalid) {
            print_r($result);
        }

        $this->assertInstanceOf(VatIdCheckValidLevelTwo::class, $result);
        $this->assertTrue($result->valid);
        $this->assertNotEmpty($result->name);
    }

    /**
     * Testing invalid vat id.
     *
     * @param string $uid Vat id
     *
     * @testWith ["ATU7231217X"]
     */
    public function testCheckInvalidVat(string $uid): void
    {
        $vatIdCheckWebservice = new VatIdCheckWs($this->sessionWs);
        $this->assertInstanceOf(VatIdCheckWs::class, $vatIdCheckWebservice);

        /** @var VatIdCheckInvalid $result */
        $result = $vatIdCheckWebservice->check($uid);
        $this->assertInstanceOf(VatIdCheckInvalid::class, $result);
        $this->assertFalse($result->valid);
    }
}
