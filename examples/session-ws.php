<?php

use CSoellinger\FonWebservices\Authentication\FonCredential;
use CSoellinger\FonWebservices\SessionWs;

// Basically you need the session webservice to get a session id
$credential = new FonCredential($tId, $tUid, $benId, $benPin);
$sessionWs = new SessionWs($credential);

// You can login by yourself if you need to know the id
// $sessionWs->login()->getID()

// Otherwise the other webservices will log you in before handling the requests
