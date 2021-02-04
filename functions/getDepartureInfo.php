<?php

function fgetDate(mysqli $mysqli, int $departureId)
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

function getTakenSeats(mysqli $mysqli, int $departureId)
{
    $sql = "SELECT COUNT(*) FROM `hvb_passengers` WHERE `tickets__ticket_id` IN (SELECT `ticket_id` FROM `hvb_ticket` WHERE `ticket_start__stops__stop_id` IN (SELECT `stop_id` FROM `hvb_stops` WHERE `departures__departure_id`=$departureId AND `payments__payment_id` IN (SELECT `payment_id` FROM `hvb_payments` WHERE `payment.reservation.created_datetime` IS NOT NULL)))";
    $result = $mysqli->query($sql);
    $numTakenSeats = (int)$result->fetch_row()[0];

    return $numTakenSeats;
}

function isNeededSeats(mysqli $mysqli, int $numPassangers, int $departureId)
{
    // Get taken seats
    $numTakenSeats = getTakenSeats($mysqli, $departureId);

    // Get provided seats
    $sql = "SELECT `train_seats` FROM `hvb_trains` WHERE `train_id` IN (SELECT `trains__train_id` FROM `hvb_departures` WHERE `departure_id`=16)";
    $result = $mysqli->query($sql);
    $numProvidedSeats = (int)$result->fetch_row()[0];

    // Calculate available seats
    $numAvailableSeats = $numProvidedSeats - $numTakenSeats;
    // Check if there is the needed seats
    if ($numPassangers <= $numAvailableSeats) {
        return true;
    } else {
        return false;
    }
}
