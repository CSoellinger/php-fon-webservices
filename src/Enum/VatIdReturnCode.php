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

namespace CSoellinger\FonWebservices\Enum;

/**
 * Return codes for VAT ID checks.
 *
 * @see https://www.bmf.gv.at/dam/jcr:e6acfe5b-f4a5-44f6-8a57-28256efdb850/BMF_UID_Abfrage_Webservice_2.pdf
 */
enum VatIdReturnCode: int
{
    /**
     * The UID of the acquirer is valid.
     */
    case Valid = 0;

    /**
     * The session ID is invalid or expired.
     */
    case SessionInvalid = -1;

    /**
     * The web service is currently unavailable due to maintenance.
     */
    case MaintenanceMode = -2;

    /**
     * A technical error has occurred.
     */
    case TechnicalError = -3;

    /**
     * This participant is not authorized for this function.
     */
    case NotAuthorized = -4;

    /**
     * The UID of the acquirer is not valid.
     */
    case Invalid = 1;

    /**
     * The UID number of the acquirer is incorrect.
     */
    case IncorrectUid = 4;

    /**
     * The UID number of the applicant is invalid.
     */
    case ApplicantUidInvalid = 5;

    /**
     * The specified member state prohibits this query.
     */
    case MemberStateProhibits = 10;

    /**
     * UID does not begin with ATU.
     */
    case NotAtuFormat = 101;

    /**
     * The requested UID can only be confirmed in level 1 because it belongs to a corporate group (VAT group).
     */
    case CorporateGroupLevel1Only = 103;

    /**
     * The requested UID can only be confirmed in level 1 because it belongs to a corporate group (VAT group).
     */
    case VatGroupLevel1Only = 104;

    /**
     * The UID number must be queried individually via FinanzOnline.
     */
    case QueryIndividually = 105;

    /**
     * The specified member state is currently not reachable.
     */
    case MemberStateUnreachable = 1511;
}
