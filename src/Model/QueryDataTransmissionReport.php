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

class QueryDataTransmissionReport
{
    public QueryDataTransmissionReportBasicDataLz $grunddatenLz;

    public QueryDataTransmissionReportBasicDataAn $grunddatenArbeitnehmer;

    public ?QueryDataTransmissionBasicDataAg $grunddatenArbeitgeber = null;

    public ?QueryDataTransmissionReportLzData $lzDaten = null;

    public function __construct()
    {
        $this->grunddatenLz = new QueryDataTransmissionReportBasicDataLz();
        $this->grunddatenArbeitnehmer = new QueryDataTransmissionReportBasicDataAn();
    }

    /**
     * @param \stdClass $response
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
