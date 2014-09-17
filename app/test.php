<?php


$alphabet      = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$requestNumber = 'REQ-';

$dateNow = new \DateTime('now UTC');
$dateWas = new \DateTime('01.01.2000 00:00:00 UTC');

$daysPart = str_pad($dateNow->diff($dateWas)->days, 5, '0', STR_PAD_LEFT);
$hourPart = $alphabet[$dateNow->format('H')];
$timePart = str_pad(($dateNow->format('i') * 60) + $dateNow->format('s'), 4, '0', STR_PAD_LEFT);

echo $dateNow->format('i') . "\n";
echo $dateNow->format('s') . "\n";

echo "\n";
echo $hourPart . "\n";
echo $daysPart . "\n";
echo $timePart . "\n";

$requestNumber .=  $daysPart.$hourPart . $timePart;

echo $requestNumber . "\n";

exit();

//$utc = new DateTimeZone('UTC');
//$time = time();
$number   = 'REQ-';
$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
//date_default_timezone_set('UTC');
$dateNow = new DateTime('now UTC');
//$dateNow->setTimezone($utc);
$dateWas = new DateTime('01.01.2000 00:00:00 UTC');

$a = $dateNow->format('i') * 60 + $dateNow->format('s');

echo $dateNow->format('Y-m-d H:i:s O P') . "\n";
echo $dateWas->format('Y-m-d H:i:s O P') . "\n";

$diff = $dateNow->diff($dateWas);
date_default_timezone_set('UTC');
$hour = $dateNow->format('H');
echo $hour . "\n";
var_dump($diff->days);
echo "\n";
echo $a;
echo "\n";
var_dump($alphabet[$hour]);
echo "\n";

echo $alphabet[0] . "\n";