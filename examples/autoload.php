<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use CSoellinger\FonWebservices\Authentication\FonCredential;
use CSoellinger\FonWebservices\SessionWs;
use Symfony\Component\Dotenv\Dotenv;

if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '.env') === false) {
    throw new Exception('.env file is needed for public examples. please make a copy from .env.example, rename and configure it.', 1);
}

$dotenv = new Dotenv();
$dotenv->usePutenv(true);
$dotenv->load(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '.env');

$tId = getenv('FON_T_ID') ?: '';
$tUid = getenv('FON_T_UID') ?: '';
$benId = getenv('FON_BEN_ID') ?: '';
$benPin = getenv('FON_BEN_PIN') ?: '';

$credential = new FonCredential((string) getenv('FON_T_ID'), (string) getenv('FON_T_UID'), (string) getenv('FON_BEN_ID'), (string) getenv('FON_BEN_PIN'));
$sessionWs = new SessionWs($credential);

$credential = new FonCredential((string) getenv('FON_TEST_DB_T_ID'), (string) getenv('FON_T_UID'), (string) getenv('FON_TEST_DB_BEN_ID'), (string) getenv('FON_TEST_DB_BEN_PIN'));
$sessionWsTest = new SessionWs($credential);

$xmlKontoRegPath = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'resources', 'test-data', 'Kontenregister.xml']);

$xmlFileUploadPath = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'resources', 'test-data', 'FileUpload.xml']);
$xmlFileUpload = file_get_contents($xmlFileUploadPath);
