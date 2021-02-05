<?php

header('Content-Type: text/html; charset=UTF-8');

$result = setlocale(LC_ALL, 'da_DK');

require "./connect.php";

require "functions/getStops.php";
require_once "functions/getDepartureInfo.php";

$departureId = $_GET["departureid"];

$date = fgetDate($mysqli, $departureId);

$firstStop = getFirstStop($mysqli, $departureId);

$lastStop = getLastStop($mysqli, $departureId);

printDepartureCards_special($mysqli, $firstStop, $lastStop, $date);
