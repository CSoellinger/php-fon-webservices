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
 * Message 109a (Mitteilung 109a) contractor payment data model.
 */
final class QueryDataTransmissionMsg109a
{
    /**
     * @var QueryDataTransmissionMsg109aBasicDataLz Message basic data
     */
    public QueryDataTransmissionMsg109aBasicDataLz $grunddatenLz;

    /**
     * @var QueryDataTransmissionMsg109aBasicDataAn Contractor basic data
     */
    public QueryDataTransmissionMsg109aBasicDataAn $grunddatenAuftragnehmer;

    /**
     * @var QueryDataTransmissionBasicDataAg|null Client/employer basic data
     */
    public ?QueryDataTransmissionBasicDataAg $grunddatenArbeitgeber = null;

    /**
     * @var QueryDataTransmissionMsg109aLzData|null Message detailed data
     */
    public ?QueryDataTransmissionMsg109aLzData $lzDaten = null;

    /**
     * Constructor - initializes required properties.
     */
    public function __construct()
    {
        $this->grunddatenLz = new QueryDataTransmissionMsg109aBasicDataLz();
        $this->grunddatenAuftragnehmer = new QueryDataTransmissionMsg109aBasicDataAn();
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

        $new->grunddatenLz = Serializer::deserialize((object) $response->grunddatenLz, QueryDataTransmissionMsg109aBasicDataLz::class);
        $new->grunddatenAuftragnehmer =
            Serializer::deserialize((object) $response->grunddatenAuftragnehmer, QueryDataTransmissionMsg109aBasicDataAn::class);

        if (property_exists($response, 'grunddatenArbeitgeber') === true) {
            $new->grunddatenArbeitgeber =
                Serializer::deserialize((object) $response->grunddatenArbeitgeber, QueryDataTransmissionBasicDataAg::class);
        }

        if (property_exists($response, 'lzDaten') === true) {
            $new->lzDaten = Serializer::deserialize((object) $response->lzDaten, QueryDataTransmissionMsg109aLzData::class);
        }

        return $new;
    }
}
