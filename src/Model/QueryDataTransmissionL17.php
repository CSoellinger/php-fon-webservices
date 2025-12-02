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

namespace CSoellinger\FonWebservices\Model;

use CSoellinger\FonWebservices\Util\Serializer;

use function property_exists;

/**
 * L17 pension statement (Pensionszettel) data model.
 */
final class QueryDataTransmissionL17
{
    /**
     * @var QueryDataTransmissionL17BasicDataLz Pension statement basic data
     */
    public QueryDataTransmissionL17BasicDataLz $grunddatenLz;

    /**
     * @var QueryDataTransmissionL17BasicDataAn Pensioner basic data
     */
    public QueryDataTransmissionL17BasicDataAn $grunddatenArbeitnehmer;

    /**
     * @var QueryDataTransmissionBasicDataAg|null Pension payer basic data
     */
    public ?QueryDataTransmissionBasicDataAg $grunddatenArbeitgeber = null;

    /**
     * @var QueryDataTransmissionL17LzData|null Pension statement detailed data
     */
    public ?QueryDataTransmissionL17LzData $lzDaten = null;

    /**
     * Constructor - initializes required properties.
     */
    public function __construct()
    {
        $this->grunddatenLz = new QueryDataTransmissionL17BasicDataLz();
        $this->grunddatenArbeitnehmer = new QueryDataTransmissionL17BasicDataAn();
    }

    /**
     * Create instance from API response.
     *
     * @param \stdClass $response The API response to deserialize
     *
     * @return self The deserialized instance
     */
    public static function createFromResponse(object $response): self
    {
        $new = new self();

        $new->grunddatenLz = Serializer::deserialize((object) $response->grunddatenLz, QueryDataTransmissionL17BasicDataLz::class);
        $new->grunddatenArbeitnehmer =
            Serializer::deserialize((object) $response->grunddatenArbeitnehmer, QueryDataTransmissionL17BasicDataAn::class);

        if (property_exists($response, 'grunddatenArbeitgeber') === true) {
            $new->grunddatenArbeitgeber =
                Serializer::deserialize((object) $response->grunddatenArbeitgeber, QueryDataTransmissionBasicDataAg::class);
        }

        if (property_exists($response, 'lzDaten') === true) {
            $new->lzDaten = Serializer::deserialize((object) $response->lzDaten, QueryDataTransmissionL17LzData::class);
        }

        return $new;
    }
}
