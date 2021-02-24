<?php

use CSoellinger\FonWebservices\FileUploadWs;

$fileUploadWs = new FileUploadWs($sessionWs);

var_dump($fileUploadWs->upload($xmlFileUpload, 'RZ', true));
// (bool) true
