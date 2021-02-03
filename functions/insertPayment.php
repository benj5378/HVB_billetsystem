<?php

function insertPayment(mysqli $mysqli, string $paymentIdDibs)
{
    $sql = "INSERT INTO `hvb_payments`(`payment_id`, `payment_id_dibs`, `payment.reservation.created_datetime`, `payment.charge.created_datetime`, `payment_consumer_name`, `payment_email_address`, `payment_email_sent`)\n"
        . "VALUES (NULL, ?, NULL, NULL, NULL, NULL, NULL)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $paymentIdDibs);
    $succes = $stmt->execute(); // $succes = true, if succes

    $paymentId = $stmt->insert_id;

    if (!$succes) {
        die($stmt->error);
    }

    return $paymentId;
}
