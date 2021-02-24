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

use CSoellinger\FonWebservices\Response\QueryDataTransmission\QueryResponse;
use stdClass;

class QueryDataTransmission
{
    /** @var null|QueryDataTransmissionL16 */
    public $l16;

    /** @var null|QueryDataTransmissionL17 */
    public $l17;

    /** @var null|QueryDataTransmissionReport */
    public $meldung;

    /** @var null|QueryDataTransmissionMsg109a */
    public $mitteilung109a;

    /** @var null|array<QueryDataTransmissionExtraExpenses> */
    public $sonderausgaben;

    /** @var null|array<QueryDataTransmissionManagementRight> */
    public $leitungsrechte;

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
            $new->l16 = QueryDataTransmissionL16::createFromResponse((object) $result->l16);
        }

        if (property_exists($result, 'l17') === true) {
            $new->l17 = QueryDataTransmissionL17::createFromResponse((object) $result->l17);
        }

        if (property_exists($result, 'meldung') === true) {
            $new->meldung = QueryDataTransmissionReport::createFromResponse((object) $result->meldung);
        }

        if (property_exists($result, 'mitteilung109a') === true) {
            $new->mitteilung109a = QueryDataTransmissionMsg109a::createFromResponse((object) $result->mitteilung109a);
        }

        if (property_exists($result, 'sonderausgaben') === true) {
            // Management right

            $new->sonderausgaben = array_map(function ($val) {
                return QueryDataTransmissionExtraExpenses::createFromResponse((object) $val);
            }, (array) $result->sonderausgaben);
        }

        if (property_exists($result, 'leitungsrechte') === true) {
            $new->leitungsrechte = array_map(function ($val) {
                return QueryDataTransmissionManagementRight::stdToClass((object) $val);
            }, (array) $result->leitungsrechte);
        }

        return $new;
    }
}
