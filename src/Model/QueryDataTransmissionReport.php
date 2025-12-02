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
 * Report/message (Meldung) data model.
 */
final class QueryDataTransmissionReport
{
    /**
     * @var QueryDataTransmissionReportBasicDataLz Report basic data
     */
    public QueryDataTransmissionReportBasicDataLz $grunddatenLz;

    /**
     * @var QueryDataTransmissionReportBasicDataAn Employee basic data
     */
    public QueryDataTransmissionReportBasicDataAn $grunddatenArbeitnehmer;

    /**
     * @var QueryDataTransmissionBasicDataAg|null Employer basic data
     */
    public ?QueryDataTransmissionBasicDataAg $grunddatenArbeitgeber = null;

    /**
     * @var QueryDataTransmissionReportLzData|null Report detailed data
     */
    public ?QueryDataTransmissionReportLzData $lzDaten = null;

    /**
     * Constructor - initializes required properties.
     */
    public function __construct()
    {
        $this->grunddatenLz = new QueryDataTransmissionReportBasicDataLz();
        $this->grunddatenArbeitnehmer = new QueryDataTransmissionReportBasicDataAn();
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

        $new->grunddatenLz = Serializer::deserialize((object) $response->grunddatenLz, QueryDataTransmissionReportBasicDataLz::class);
        $new->grunddatenArbeitnehmer =
            Serializer::deserialize((object) $response->grunddatenArbeitnehmer, QueryDataTransmissionReportBasicDataAn::class);

        if (property_exists($response, 'grunddatenArbeitgeber') === true) {
            $new->grunddatenArbeitgeber =
                Serializer::deserialize((object) $response->grunddatenArbeitgeber, QueryDataTransmissionBasicDataAg::class);
        }

        if (property_exists($response, 'lzDaten') === true) {
            $new->lzDaten = Serializer::deserialize((object) $response->lzDaten, QueryDataTransmissionReportLzData::class);
        }

        return $new;
    }
}
