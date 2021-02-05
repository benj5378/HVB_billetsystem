<?php

header('Content-Type: text/html; charset=UTF-8');

$result = setlocale(LC_ALL, 'da_DK');

include_once("./credentials.php");

// Create connection
$mysqli = new mysqli($servername, $username, $password, $db);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

require "functions/getStops.php";
require_once "functions/getDepartureInfo.php";

$departureId = $_GET["departureid"];

$date = fgetDate($mysqli, $departureId);

$firstStop = getFirstStop($mysqli, $departureId);

$lastStop = getLastStop($mysqli, $departureId);

printDepartureCards_special($mysqli, $firstStop, $lastStop, $date);
