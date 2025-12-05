<?php

declare(strict_types=1);

use CSoellinger\FonWebservices\VatIdCheckWs;

$checkVatIdWs = new VatIdCheckWs($sessionWs);

// Check at level one only returns valid
var_dump($checkVatIdWs->check('ATU36975500'));
// CSoellinger\FonWebservices\Model\VatIdCheckValidLevelOne
//   valid => (bool) true

// At level two you also get name and address of the organisation if available
var_dump($checkVatIdWs->check('ATU36975500', VatIdCheckWs::LEVEL_FULL_CHECK));
// CSoellinger\FonWebservices\Model\VatIdCheckValidLevelTwo
//   name => (string) 'McDonald's Franchise GmbH'
//   address => (string) 'Campus 21,Liebermannst A01601 AT-2345 Brunn am Gebirge'
//   valid => (bool) true
