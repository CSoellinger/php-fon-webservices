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

class QueryDataTransmissionL16
{
    public QueryDataTransmissionL16BasicDataLz $grunddatenLz;

    public QueryDataTransmissionL16BasicDataAn $grunddatenArbeitnehmer;

    public ?QueryDataTransmissionBasicDataAg $grunddatenArbeitgeber = null;

    public ?QueryDataTransmissionL16LzData $lzDaten = null;

    public function __construct()
    {
        $this->grunddatenLz = new QueryDataTransmissionL16BasicDataLz();
        $this->grunddatenArbeitnehmer = new QueryDataTransmissionL16BasicDataAn();
    }

    public static function createFromResponse(stdClass $response): self
    {
        $new = new self();

        $new->grunddatenLz = QueryDataTransmissionL16BasicDataLz::stdToClass((object) $response->grunddatenLz);
        $new->grunddatenArbeitnehmer =
            QueryDataTransmissionL16BasicDataAn::stdToClass((object) $response->grunddatenArbeitnehmer);

        if (property_exists($response, 'grunddatenArbeitgeber') === true) {
            $new->grunddatenArbeitgeber =
                QueryDataTransmissionBasicDataAg::stdToClass((object) $response->grunddatenArbeitgeber);
        }

        if (property_exists($response, 'lzDaten') === true) {
            $new->lzDaten = QueryDataTransmissionL16LzData::createFromResponse((object) $response->lzDaten);
        }

        return $new;
    }
}
