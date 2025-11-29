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

use stdClass;

class QueryDataTransmissionMsg109a
{
    public QueryDataTransmissionMsg109aBasicDataLz $grunddatenLz;

    public QueryDataTransmissionMsg109aBasicDataAn $grunddatenAuftragnehmer;

    public ?QueryDataTransmissionBasicDataAg $grunddatenArbeitgeber = null;

    public ?QueryDataTransmissionMsg109aLzData $lzDaten = null;

    public function __construct()
    {
        $this->grunddatenLz = new QueryDataTransmissionMsg109aBasicDataLz();
        $this->grunddatenAuftragnehmer = new QueryDataTransmissionMsg109aBasicDataAn();
    }

    public static function createFromResponse(stdClass $response): self
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
