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

use function property_exists;

use stdClass;

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

    public static function createFromResponse(stdClass $response): self
    {
        $new = new self();

        $new->grunddatenLz = QueryDataTransmissionReportBasicDataLz::stdToClass((object) $response->grunddatenLz);
        $new->grunddatenArbeitnehmer =
            QueryDataTransmissionReportBasicDataAn::stdToClass((object) $response->grunddatenArbeitnehmer);

        if (property_exists($response, 'grunddatenArbeitgeber') === true) {
            $new->grunddatenArbeitgeber =
                QueryDataTransmissionBasicDataAg::stdToClass((object) $response->grunddatenArbeitgeber);
        }

        if (property_exists($response, 'lzDaten') === true) {
            $new->lzDaten = QueryDataTransmissionReportLzData::stdToClass((object) $response->lzDaten);
        }

        return $new;
    }
}
