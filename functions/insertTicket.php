<?php

function insertTicket(mysqli $mysqli, int $startStopId, int $endStopId, int $paymentId) // Should be renamed to last stop id
{
    $sql = "INSERT INTO `hvb_ticket`(`ticket_id`, `ticket_qr`, `ticket_valid`, `ticket_reserved_compartments`, `ticket_start__stops__stop_id`, `ticket_end__stops__stop_id`, `payments__payment_id`)\n"
        . "VALUES (NULL, uuid(), 0, NULL, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("iii", $startStopId, $endStopId, $paymentId);
    $succes = $stmt->execute();

    if (!$succes) {
        die($stmt->error);
    }

    $ticketId = $stmt->insert_id;

    return $ticketId;
}
