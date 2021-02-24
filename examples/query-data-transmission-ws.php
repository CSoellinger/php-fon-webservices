<?php

use CSoellinger\FonWebservices\QueryDataTransmissionWs;

$queryDataTransmissionWs = new QueryDataTransmissionWs($sessionWsTest);

dump($queryDataTransmissionWs->query('382572311'));
