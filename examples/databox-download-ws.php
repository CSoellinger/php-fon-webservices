<?php

declare(strict_types=1);

use CSoellinger\FonWebservices\DataboxDownloadWs;

$databoxDownloadWs = new DataboxDownloadWs($sessionWsTest);

// Get a list of your databox
$databoxList = $databoxDownloadWs->get();
var_dump($databoxList);
// array
//   0 =>
//     CSoellinger\FonWebservices\Model\DataboxDownloadListItem
//       stnr => (string) '99 999/9999'
//       name => (string) 'Mustermann Max'
//       anbringen => (string) 'STB-ZUSI'
//       zrvon => (string) ''
//       zrbis => (string) ''
//       datbesch => (string) '2019-07-15+02:00'
//       erltyp => (string) 'B'
//       fileart => (string) 'PDF'
//       ts_zust => (string) '2020-11-02T07:04:10.044+01:00'
//       applkey => (string) '2019-07-15-11.45.07.000000'
//       filebez => (string) '99_9999999_B_2008-11-20_2008-11-20-100948804630.PDF'
//       status => (string) ''

// Get one entry of your databox
$entry = $databoxDownloadWs->getEntry($databoxList[0]->applkey);
var_dump($entry);
// (string) 'JVBERi0xLjQNJeLjz9MNCjYgMCBvYmoNPDwvTGluZWFyaXplZCAxL0wgNTk1Ny9PIDgvRSAxNzAzL'...
