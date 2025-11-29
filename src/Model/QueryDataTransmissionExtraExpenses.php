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

use function array_map;

use CSoellinger\FonWebservices\Util\Serializer;

use function property_exists;

use stdClass;

class QueryDataTransmissionExtraExpenses
{
    /** @var string ... */
    public string $kategorie = '';

    /** @var string ... */
    public string $kategorieText = '';

    /** @var float ... */
    public float $betrag = 0.0;

    /** @var array<QueryDataTransmissionExtraExpensesDetail>|null ... */
    public ?array $sonderausgabenDetail = null;

    public static function createFromResponse(stdClass $response): self
    {
        $new = Serializer::deserialize($response, self::class);

        if (property_exists($response, 'sonderausgabenDetail')) {
            $new->sonderausgabenDetail = array_map(
                fn ($val) => Serializer::deserialize((object) $val, QueryDataTransmissionExtraExpensesDetail::class),
                (array) $response->sonderausgabenDetail,
            );
        }

        return $new;
    }
}
