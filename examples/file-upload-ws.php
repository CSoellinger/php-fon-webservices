<?php

declare(strict_types=1);

use CSoellinger\FonWebservices\FileUploadWs;

$fileUploadWs = new FileUploadWs($sessionWs);

var_dump($fileUploadWs->upload($xmlFileUpload, 'RZ', true));
// (bool) true
