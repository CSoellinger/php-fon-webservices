<?php

declare(strict_types=1);

use CSoellinger\FonWebservices\FileUploadWs;

$fileUploadWs = new FileUploadWs($sessionWs);

dump($fileUploadWs->upload($xmlFileUpload, 'RZ', true));
// (bool) true
