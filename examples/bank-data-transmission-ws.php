<?php

use CSoellinger\FonWebservices\BankDataTransmissionWs;
use CSoellinger\FonWebservices\Enum\BankDataType;

$bankDataTransmissionWs = new BankDataTransmissionWs($sessionWs);

var_dump($bankDataTransmissionWs->upload($xmlKontoReg, BankDataType::AccountRegistration, true));
// (bool) true
