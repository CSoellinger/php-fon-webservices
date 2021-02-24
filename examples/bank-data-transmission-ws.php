<?php

use CSoellinger\FonWebservices\BankDataTransmissionWs;

$bankDataTransmissionWs = new BankDataTransmissionWs($sessionWs);

var_dump($bankDataTransmissionWs->upload($xmlKontoReg, 'KTOREG', true));
// (bool) true
