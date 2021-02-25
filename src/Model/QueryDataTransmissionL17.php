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

use function property_exists;

class QueryDataTransmissionL17
{
    public QueryDataTransmissionL17BasicDataLz $grunddatenLz;

    public QueryDataTransmissionL17BasicDataAn $grunddatenArbeitnehmer;

    public ?QueryDataTransmissionBasicDataAg $grunddatenArbeitgeber = null;

    public ?QueryDataTransmissionL17LzData $lzDaten = null;

    public function __construct()
    {
        $this->grunddatenLz = new QueryDataTransmissionL17BasicDataLz();
        $this->grunddatenArbeitnehmer = new QueryDataTransmissionL17BasicDataAn();
    }

    public static function createFromResponse(stdClass $response): self
    {
        $new = new self();

        $new->grunddatenLz = QueryDataTransmissionL17BasicDataLz::stdToClass((object) $response->grunddatenLz);
        $new->grunddatenArbeitnehmer =
            QueryDataTransmissionL17BasicDataAn::stdToClass((object) $response->grunddatenArbeitnehmer);

        if (property_exists($response, 'grunddatenArbeitgeber') === true) {
            $new->grunddatenArbeitgeber =
                QueryDataTransmissionBasicDataAg::stdToClass((object) $response->grunddatenArbeitgeber);
        }

        if (property_exists($response, 'lzDaten') === true) {
            $new->lzDaten = QueryDataTransmissionL17LzData::stdToClass((object) $response->lzDaten);
        }

        return $new;
    }
}
