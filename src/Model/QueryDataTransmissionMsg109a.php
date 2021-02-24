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

class QueryDataTransmissionMsg109a
{
    /** @var QueryDataTransmissionMsg109aBasicDataLz */
    public $grunddatenLz;

    /** @var QueryDataTransmissionMsg109aBasicDataAn */
    public $grunddatenAuftragnehmer;

    /** @var QueryDataTransmissionBasicDataAg|null */
    public $grunddatenArbeitgeber;

    /** @var QueryDataTransmissionMsg109aLzData|null */
    public $lzDaten;

    public function __construct()
    {
        $this->grunddatenLz = new QueryDataTransmissionMsg109aBasicDataLz();
        $this->grunddatenAuftragnehmer = new QueryDataTransmissionMsg109aBasicDataAn();
    }

    public static function createFromResponse(stdClass $response): self
    {
        $new = new self();

        $new->grunddatenLz = QueryDataTransmissionMsg109aBasicDataLz::stdToClass((object) $response->grunddatenLz);
        $new->grunddatenAuftragnehmer =
            QueryDataTransmissionMsg109aBasicDataAn::stdToClass((object) $response->grunddatenAuftragnehmer);

        if (property_exists($response, 'grunddatenArbeitgeber') === true) {
            $new->grunddatenArbeitgeber =
                QueryDataTransmissionBasicDataAg::stdToClass((object) $response->grunddatenArbeitgeber);
        }

        if (property_exists($response, 'lzDaten') === true) {
            $new->lzDaten = QueryDataTransmissionMsg109aLzData::stdToClass((object) $response->lzDaten);
        }

        return $new;
    }
}
