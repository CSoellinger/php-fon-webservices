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

/**
 * Special expenses (Sonderausgaben) data model.
 */
final class QueryDataTransmissionExtraExpenses
{
    /**
     * @var string Category code
     */
    public string $kategorie = '';

    /**
     * @var string Category text/description
     */
    public string $kategorieText = '';

    /**
     * @var float Amount/value
     */
    public float $betrag = 0.0;

    /**
     * @var array<QueryDataTransmissionExtraExpensesDetail>|null Detailed expense items
     */
    public ?array $sonderausgabenDetail = null;

    /**
     * Create instance from API response.
     *
     * @param \stdClass $response The API response to deserialize
     *
     * @return self The deserialized instance
     */
    public static function createFromResponse(object $response): self
    {
        $new = Serializer::deserialize($response, self::class);

        if (property_exists($response, 'sonderausgabenDetail')) {
            $new->sonderausgabenDetail = array_map(
                fn ($val): object => Serializer::deserialize((object) $val, QueryDataTransmissionExtraExpensesDetail::class),
                (array) $response->sonderausgabenDetail,
            );
        }

        return $new;
    }
}
