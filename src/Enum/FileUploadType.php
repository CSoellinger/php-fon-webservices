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
 * File upload types for FinanzOnline submissions.
 *
 * @see https://www.bmf.gv.at/dam/jcr:7f3258d4-5d58-455d-9bfe-a352a4effd73/BMF_File_Upload_Webservice_2.pdf
 */
enum FileUploadType: string
{
    case BET = 'BET';
    case BIL = 'BIL';
    case DUE = 'DUE';
    case EUST = 'EUST';
    case FPH = 'FPH';
    case FVAN = 'FVAN';
    case IVF = 'IVF';
    case JAHR_ERKL = 'JAHR_ERKL';
    case JAB = 'JAB';
    case KA1 = 'KA1';
    case KOM = 'KOM';
    case KOMU = 'KOMU';
    case LFH = 'LFH';
    case L1 = 'L1';
    case NOVA = 'NOVA';
    case RZ = 'RZ';
    case SB = 'SB';
    case SBS = 'SBS';
    case SBZ = 'SBZ';
    case STAB = 'STAB';
    case TVW = 'TVW';
    case UEB = 'UEB';
    case UEB_SA = 'UEB_SA';
    case U13 = 'U13';
    case U30 = 'U30';
    case VAT = 'VAT';
    case VATAB = 'VATAB';
    case VPDGD = 'VPDGD';
    case ZEAN = 'ZEAN';
    case Type107 = '107';
    case Type107AB = '107AB';
    case Type108 = '108';
    case Type108AB = '108AB';
    case SOER = 'SOER';
    case DIGI = 'DIGI';
}
