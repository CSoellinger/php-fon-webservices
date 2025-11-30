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

use CSoellinger\FonWebservices\Response\QueryDataTransmission\QueryResponse;
use CSoellinger\FonWebservices\Util\Serializer;

use function property_exists;

use stdClass;

class QueryDataTransmission
{
    public ?QueryDataTransmissionL16 $l16 = null;

    public ?QueryDataTransmissionL17 $l17 = null;

    public ?QueryDataTransmissionReport $meldung = null;

    public ?QueryDataTransmissionMsg109a $mitteilung109a = null;

    /** @var array<QueryDataTransmissionExtraExpenses>|null */
    public ?array $sonderausgaben = null;

    /** @var array<QueryDataTransmissionManagementRight>|null */
    public ?array $leitungsrechte = null;

    /**
     * Undocumented function.
     *
     * @param QueryResponse|stdClass $response
     */
    public static function createFromResponse($response): self
    {
        $result = (object) $response->result;
        $new = new self();

        if (property_exists($result, 'l16') === true) {
            /** @var stdClass $l16Data */
            $l16Data = (object) $result->l16;
            $new->l16 = QueryDataTransmissionL16::createFromResponse($l16Data);
        }

        if (property_exists($result, 'l17') === true) {
            /** @var stdClass $l17Data */
            $l17Data = (object) $result->l17;
            $new->l17 = QueryDataTransmissionL17::createFromResponse($l17Data);
        }

        if (property_exists($result, 'meldung') === true) {
            /** @var stdClass $meldungData */
            $meldungData = (object) $result->meldung;
            $new->meldung = QueryDataTransmissionReport::createFromResponse($meldungData);
        }

        if (property_exists($result, 'mitteilung109a') === true) {
            /** @var stdClass $mitteilung109aData */
            $mitteilung109aData = (object) $result->mitteilung109a;
            $new->mitteilung109a = QueryDataTransmissionMsg109a::createFromResponse($mitteilung109aData);
        }

        if (property_exists($result, 'sonderausgaben') === true) {
            // Management right

            $new->sonderausgaben = array_map(
                function ($val) {
                    /** @var stdClass $valData */
                    $valData = (object) $val;

                    return QueryDataTransmissionExtraExpenses::createFromResponse($valData);
                },
                (array) $result->sonderausgaben,
            );
        }

        if (property_exists($result, 'leitungsrechte') === true) {
            $new->leitungsrechte = array_map(
                fn ($val) => Serializer::deserialize((object) $val, QueryDataTransmissionManagementRight::class),
                (array) $result->leitungsrechte,
            );
        }

        return $new;
    }
}
