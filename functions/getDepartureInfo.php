<?php

function fgetDate($mysqli, $departureId)
{

    $sql = "SELECT CAST(`stop_departure_time` AS DATE) FROM `hvb_stops`\n"
        . "WHERE `stop_departure_time` IN ( SELECT MIN(`stop_departure_time`) FROM `hvb_stops` GROUP BY `departures__departure_id` ) \n"
        . "AND `departures__departure_id`=?\n"
        . "ORDER BY `stop_departure_time` ASC";


    $stmtDate = $mysqli->prepare($sql);
    $stmtDate->bind_param("i", $departureId);
    $stmtDate->execute();
    $result = $stmtDate->get_result();
    $date = mysqli_fetch_row($result)[0]; // Fetch result and get first row with date

    return $date;
}
