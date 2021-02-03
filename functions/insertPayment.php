<?php

function insertPayment(mysqli $mysqli, string $paymentIdDibs)
{

    $sql = "INSERT INTO `hvb_payments`(`payment_id`, `payment_id_dibs`, `payment_datetime`, `payment_email_address`, `payment_email_sent`)\n"
        . "VALUES (NULL, ?, NULL, NULL, NULL)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $paymentIdDibs);
    $stmt->execute();

    $paymentId = $stmt->insert_id;

    return $paymentId;
}
