<?php

/**
 * This file is part of csoellinger/php-fon-webservices
 *
 * csoellinger/php-fon-webservices is free and unencumbered software released
 * into the public domain. For more information, please view the
 * UNLICENSE file that was distributed with this source code.
 *
 * @license https://unlicense.org The Unlicense
 */

declare(strict_types=1);

namespace CSoellinger\Test\FonWebservices\Authentication;

use CSoellinger\FonWebservices\Authentication\FonCredential;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Testing finanzonline credential class
 *
 * @internal
 *
 * @covers \CSoellinger\FonWebservices\Authentication\FonCredential
 */
class FonCredentialTest extends TestCase
{
    /**
     * @testWith ["11111111k111", "ATU11111111", "testuserid1", "aaAAaaaAAAaaaaAaA7Aaa6aAaa6AaaaE"]
     */
    public function testInitialize(string $tId, string $tUid, string $benId, string $benPin): void
    {
        $fonCredential = new FonCredential($tId, $tUid, $benId, $benPin);

        $this->assertNotEmpty($fonCredential->teId);
        $this->assertNotEmpty($fonCredential->teUid);
        $this->assertNotEmpty($fonCredential->benId);
        $this->assertNotEmpty($fonCredential->benPin);
    }

    /**
     * Test will throw an exception at every data set
     *
     * @throws InvalidArgumentException
     *
     * @testWith ["", "ATU11111111", "testuserid1", "aaAAaaaAAAaaaaAaA7Aaa6aAaa6AaaaE"]
     *           ["X", "ATU11111111", "testuserid1", "aaAAaaaAAAaaaaAaA7Aaa6aAaa6AaaaE"]
     *           ["11111111k111", "X", "testuserid1", "aaAAaaaAAAaaaaAaA7Aaa6aAaa6AaaaE"]
     *           ["11111111k111", "ATU11111111", "X", "aaAAaaaAAAaaaaAaA7Aaa6aAaa6AaaaE"]
     *           ["11111111k111", "ATU11111111", "testuserid1", "X"]
     */
    public function testInvalidArguments(string $tId, string $tUid, string $benId, string $benPin): void
    {
        $this->expectException(InvalidArgumentException::class);

        new FonCredential($tId, $tUid, $benId, $benPin);
    }
}
