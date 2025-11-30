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
 * L16 wage statement (Lohnzettel) data model.
 */
class QueryDataTransmissionL16
{
    /**
     * @var QueryDataTransmissionL16BasicDataLz Wage statement basic data
     */
    public QueryDataTransmissionL16BasicDataLz $grunddatenLz;

    /**
     * @var QueryDataTransmissionL16BasicDataAn Employee basic data
     */
    public QueryDataTransmissionL16BasicDataAn $grunddatenArbeitnehmer;

    /**
     * @var QueryDataTransmissionBasicDataAg|null Employer basic data
     */
    public ?QueryDataTransmissionBasicDataAg $grunddatenArbeitgeber = null;

    /**
     * @var QueryDataTransmissionL16LzData|null Wage statement detailed data
     */
    public ?QueryDataTransmissionL16LzData $lzDaten = null;

    /**
     * Constructor - initializes required properties.
     */
    public function __construct()
    {
        $this->grunddatenLz = new QueryDataTransmissionL16BasicDataLz();
        $this->grunddatenArbeitnehmer = new QueryDataTransmissionL16BasicDataAn();
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

        $new->grunddatenLz = Serializer::deserialize((object) $response->grunddatenLz, QueryDataTransmissionL16BasicDataLz::class);
        $new->grunddatenArbeitnehmer =
            Serializer::deserialize((object) $response->grunddatenArbeitnehmer, QueryDataTransmissionL16BasicDataAn::class);

        if (property_exists($response, 'grunddatenArbeitgeber') === true) {
            $new->grunddatenArbeitgeber =
                Serializer::deserialize((object) $response->grunddatenArbeitgeber, QueryDataTransmissionBasicDataAg::class);
        }

        if (property_exists($response, 'lzDaten') === true) {
            /** @var \stdClass $lzDatenData */
            $lzDatenData = (object) $response->lzDaten;
            $new->lzDaten = QueryDataTransmissionL16LzData::createFromResponse($lzDatenData);
        }

        return $new;
    }
}
