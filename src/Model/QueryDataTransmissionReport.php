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

use stdClass;

class QueryDataTransmissionReport
{
    /** @var QueryDataTransmissionReportBasicDataLz */
    public $grunddatenLz;

    /** @var QueryDataTransmissionReportBasicDataAn */
    public $grunddatenArbeitnehmer;

    /** @var QueryDataTransmissionBasicDataAg|null */
    public $grunddatenArbeitgeber;

    /** @var QueryDataTransmissionReportLzData|null */
    public $lzDaten;

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
