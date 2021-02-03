<?php

function insertPassengers(mysqli $mysqli, int $ticketId, array $passengers)
{
    $sql = "INSERT INTO `hvb_passengers`(`passenger_id`, `ticket_type`, `tickets__ticket_id`)\n"
        . "VALUES (NULL, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("si", $ticketType, $ticketId);

    foreach ($passengers as $passenger => $amount) {
        $ticketType = $passenger;

        for ($i = 0; $i < $amount; $i++) {
            $succes = $stmt->execute();
            if (!$succes) {
                die($stmt->error);
            }
        }
    }
}
