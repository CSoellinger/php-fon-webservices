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
 * Databox types for filtering databox downloads.
 *
 * @see https://www.bmf.gv.at/dam/jcr:02d9e9ec-a4f6-4c58-aa3d-aadb8946aed2/BMF_DataBox_Download_Webservice_2.pdf
 */
enum DataboxType: string
{
    case AE = 'AE';
    case AF = 'AF';
    case AK = 'AK';
    case AZ = 'AZ';
    case B = 'B';
    case DL = 'DL';
    case E = 'E';
    case EU = 'EU';
    case FB = 'FB';
    case GM = 'GM';
    case I = 'I';
    case KG = 'KG';
    case M = 'M';
    case P = 'P';
    case QL = 'QL';
    case SS = 'SS';
}
