<?php

declare(strict_types=1);

use CSoellinger\FonWebservices\QueryDataTransmissionWs;

$queryDataTransmissionWs = new QueryDataTransmissionWs($sessionWsTest);

var_dump($queryDataTransmissionWs->query('382572311'));
